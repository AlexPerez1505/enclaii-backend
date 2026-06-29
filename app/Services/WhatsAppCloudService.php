<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatsAppCloudService
{
    /**
     * @return array{message_id: string, recipient: string}
     *
     * @throws ConnectionException
     * @throws RuntimeException
     */
    public function sendText(string $recipient, string $message): array
    {
        $token = (string) config('services.whatsapp.access_token');
        $phoneNumberId = (string) config('services.whatsapp.phone_number_id');
        $version = (string) config('services.whatsapp.api_version', 'v21.0');

        if ($token === '' || $phoneNumberId === '') {
            throw new RuntimeException('La API de WhatsApp no está configurada completamente.');
        }

        if (! preg_match('/^v\d+\.\d+$/', $version)) {
            throw new RuntimeException('La versión configurada de WhatsApp API no es válida.');
        }

        $recipient = $this->normalizePhone($recipient);

        if (! preg_match('/^\d{8,15}$/', $recipient)) {
            throw new RuntimeException('El teléfono del paciente no tiene un formato internacional válido.');
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->timeout(20)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipient,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $message,
                ],
            ]);

        if ($response->failed()) {
            $apiMessage = $response->json('error.message');
            $message = is_string($apiMessage) && $apiMessage !== ''
                ? $apiMessage
                : 'Meta rechazó el envío del mensaje.';

            throw new RuntimeException($message);
        }

        $messageId = $response->json('messages.0.id');

        if (! is_string($messageId) || $messageId === '') {
            throw new RuntimeException('Meta no devolvió el identificador del mensaje.');
        }

        return [
            'message_id' => $messageId,
            'recipient' => $recipient,
        ];
    }

    public function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }
}
