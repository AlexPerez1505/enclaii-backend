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
            $message = $this->formatMetaError($response->json('error', []));

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

    private function formatMetaError(mixed $error): string
    {
        if (! is_array($error)) {
            return 'Meta rechazo el envio del mensaje.';
        }

        $parts = [];
        $message = data_get($error, 'message');
        $details = data_get($error, 'error_data.details');
        $code = data_get($error, 'code');
        $subcode = data_get($error, 'error_subcode');

        if (is_string($message) && $message !== '') {
            $parts[] = $message;
        }

        if (is_string($details) && $details !== '') {
            $parts[] = $details;
        }

        if (is_scalar($code) && $code !== '') {
            $parts[] = 'Codigo Meta: '.$code;
        }

        if (is_scalar($subcode) && $subcode !== '') {
            $parts[] = 'Subcodigo Meta: '.$subcode;
        }

        return $parts !== []
            ? implode(' | ', array_unique($parts))
            : 'Meta rechazo el envio del mensaje.';
    }
}
