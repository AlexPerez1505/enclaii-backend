<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\WhatsAppMessage;
use App\Services\WhatsAppCloudService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class WhatsAppController extends Controller
{
    public function __construct(private readonly WhatsAppCloudService $whatsapp)
    {
    }

    public function index(Request $request)
    {
        $patients = Paciente::query()
            ->whereNotNull('telefono')
            ->where('telefono', '<>', '')
            ->with(['whatsappMessages' => fn ($query) => $query->latest()->limit(1)])
            ->orderBy('nombre_completo')
            ->get();

        $contacts = $patients->map(function (Paciente $patient): array {
            $latest = $patient->whatsappMessages->first();
            $initials = collect(preg_split('/\s+/', trim($patient->nombre_completo)) ?: [])
                ->filter()
                ->take(2)
                ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
                ->implode('');

            return [
                'id' => $patient->id,
                'name' => $patient->nombre_completo,
                'initials' => $initials !== '' ? $initials : 'PX',
                'phone_masked' => $this->maskPhone($patient->telefono),
                'preview' => $latest?->body ?? 'Sin mensajes todavía',
                'time' => $latest?->created_at?->diffForHumans(short: true) ?? '',
                'unread' => $patient->whatsappMessages()
                    ->where('direction', 'inbound')
                    ->where('status', 'received')
                    ->count(),
            ];
        });

        $configured = filled(config('services.whatsapp.access_token'))
            && filled(config('services.whatsapp.phone_number_id'));
        $webhookConfigured = filled(config('services.whatsapp.webhook_verify_token'))
            && filled(config('services.whatsapp.app_secret'));

        return view('mensajes.dashboard', [
            'whatsappContacts' => $contacts,
            'whatsappConfigured' => $configured,
            'whatsappWebhookConfigured' => $webhookConfigured,
            'whatsappBusinessPhone' => $this->maskPhone(config('services.whatsapp.phone_number')),
            'whatsappLaunchContext' => [
                'channel' => $request->query('canal'),
                'patient' => $request->query('paciente'),
                'patient_id' => $request->integer('paciente_id') ?: null,
                'study' => $request->query('estudio'),
                'video' => $request->query('video'),
                'image' => $request->query('imagen'),
                'frame' => $request->query('fotograma'),
                'type' => $request->query('tipo'),
                'date' => $request->query('fecha'),
                'diagnosis' => $request->query('diagnostico'),
            ],
        ]);
    }

    public function messages(Paciente $paciente): JsonResponse
    {
        $messages = $paciente->whatsappMessages()
            ->oldest()
            ->limit(200)
            ->get();

        $paciente->whatsappMessages()
            ->where('direction', 'inbound')
            ->where('status', 'received')
            ->update([
                'status' => 'read',
                'read_at' => now(),
            ]);

        return response()->json([
            'messages' => $messages->map(fn (WhatsAppMessage $message): array => [
                'id' => $message->id,
                'direction' => $message->direction,
                'type' => $message->type,
                'body' => $message->body ?? '',
                'status' => $message->status,
                'error' => $message->error,
                'time' => ($message->sent_at ?? $message->created_at)?->format('H:i'),
            ])->values(),
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            'message' => ['required', 'string', 'max:4096'],
        ]);

        $patient = Paciente::findOrFail($validated['paciente_id']);
        $recipient = $this->whatsapp->normalizePhone($patient->telefono);

        if ($recipient === '') {
            return response()->json([
                'message' => 'El paciente no tiene un teléfono registrado.',
            ], 422);
        }

        if (! $this->hasOpenCustomerServiceWindow($patient, $recipient)) {
            return response()->json([
                'message' => 'WhatsApp no permite iniciar una conversacion con texto libre. El paciente debe escribir primero o debes enviar una plantilla aprobada de WhatsApp.',
            ], 422);
        }

        $message = WhatsAppMessage::create([
            'paciente_id' => $patient->id,
            'user_id' => $request->user()?->id,
            'wa_id' => $recipient,
            'direction' => 'outbound',
            'type' => 'text',
            'body' => $validated['message'],
            'status' => 'pending',
        ]);

        try {
            $result = $this->whatsapp->sendText($recipient, $validated['message']);

            $message->update([
                'meta_message_id' => $result['message_id'],
                'wa_id' => $result['recipient'],
                'status' => 'accepted',
                'sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            $message->update([
                'status' => 'failed',
                'error' => Str::limit($exception->getMessage(), 1000),
                'failed_at' => now(),
            ]);

            return response()->json([
                'message' => $exception instanceof RuntimeException
                    ? $exception->getMessage()
                    : 'No se pudo conectar con WhatsApp en este momento.',
            ], 502);
        }

        return response()->json([
            'message' => 'Mensaje aceptado por WhatsApp.',
            'data' => [
                'id' => $message->id,
                'direction' => $message->direction,
                'body' => $message->body,
                'status' => $message->status,
                'error' => $message->error,
                'time' => $message->sent_at?->format('H:i'),
            ],
        ], 201);
    }

    public function verifyWebhook(Request $request): Response
    {
        $mode = (string) ($request->query('hub_mode') ?? $request->query('hub.mode', ''));
        $token = (string) ($request->query('hub_verify_token') ?? $request->query('hub.verify_token', ''));
        $challenge = (string) ($request->query('hub_challenge') ?? $request->query('hub.challenge', ''));
        $expectedToken = (string) config('services.whatsapp.webhook_verify_token');

        if ($mode === 'subscribe' && $expectedToken !== '' && hash_equals($expectedToken, $token)) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Webhook no autorizado.', 403);
    }

    public function webhook(Request $request): JsonResponse
    {
        $appSecret = (string) config('services.whatsapp.app_secret');

        if ($appSecret === '') {
            return response()->json([
                'message' => 'WHATSAPP_APP_SECRET no está configurado.',
            ], 503);
        }

        $signature = (string) $request->header('X-Hub-Signature-256', '');
        $expectedSignature = 'sha256='.hash_hmac('sha256', $request->getContent(), $appSecret);

        if ($signature === '' || ! hash_equals($expectedSignature, $signature)) {
            return response()->json(['message' => 'Firma inválida.'], 403);
        }

        $payload = $request->json()->all();

        foreach (data_get($payload, 'entry', []) as $entry) {
            foreach (data_get($entry, 'changes', []) as $change) {
                if (data_get($change, 'field') !== 'messages') {
                    continue;
                }

                $value = data_get($change, 'value', []);
                $this->storeIncomingMessages((array) $value);
                $this->updateMessageStatuses((array) $value);
            }
        }

        return response()->json(['received' => true]);
    }

    private function storeIncomingMessages(array $value): void
    {
        foreach ((array) data_get($value, 'messages', []) as $incoming) {
            $messageId = (string) data_get($incoming, 'id', '');
            $waId = $this->whatsapp->normalizePhone((string) data_get($incoming, 'from', ''));

            if ($messageId === '' || $waId === '') {
                continue;
            }

            $type = (string) data_get($incoming, 'type', 'unknown');
            $body = match ($type) {
                'text' => data_get($incoming, 'text.body'),
                'button' => data_get($incoming, 'button.text'),
                'interactive' => data_get($incoming, 'interactive.button_reply.title')
                    ?? data_get($incoming, 'interactive.list_reply.title'),
                'image' => data_get($incoming, 'image.caption', '[Imagen]'),
                'video' => data_get($incoming, 'video.caption', '[Video]'),
                'document' => data_get($incoming, 'document.filename', '[Documento]'),
                'audio' => '[Audio]',
                default => '['.Str::headline($type).']',
            };

            $timestamp = data_get($incoming, 'timestamp');
            $patient = $this->findPatientByWhatsAppId($waId);

            $message = WhatsAppMessage::firstOrCreate(
                ['meta_message_id' => $messageId],
                [
                    'paciente_id' => $patient?->id,
                    'wa_id' => $waId,
                    'direction' => 'inbound',
                    'type' => $type,
                    'body' => is_string($body) ? $body : null,
                    'status' => 'received',
                    'sent_at' => is_numeric($timestamp)
                        ? Carbon::createFromTimestamp((int) $timestamp)
                        : now(),
                ],
            );

            if ($message->wasRecentlyCreated) {
                $this->sendAutomaticReplyWhenNeeded($message);
            }
        }
    }

    private function updateMessageStatuses(array $value): void
    {
        foreach ((array) data_get($value, 'statuses', []) as $statusData) {
            $messageId = (string) data_get($statusData, 'id', '');
            $status = (string) data_get($statusData, 'status', '');

            if ($messageId === '' || $status === '') {
                continue;
            }

            $message = WhatsAppMessage::where('meta_message_id', $messageId)->first();

            if (! $message) {
                continue;
            }

            $timestamp = data_get($statusData, 'timestamp');
            $occurredAt = is_numeric($timestamp)
                ? Carbon::createFromTimestamp((int) $timestamp)
                : now();

            $updates = ['status' => $status];

            if ($status === 'sent') {
                $updates['sent_at'] = $occurredAt;
            } elseif ($status === 'delivered') {
                $updates['delivered_at'] = $occurredAt;
            } elseif ($status === 'read') {
                $updates['read_at'] = $occurredAt;
            } elseif ($status === 'failed') {
                $updates['failed_at'] = $occurredAt;
                $updates['error'] = Str::limit((string) (
                    data_get($statusData, 'errors.0.error_data.details')
                    ?? data_get($statusData, 'errors.0.title')
                    ?? 'Meta informó que el mensaje falló.'
                ), 1000);
            }

            $message->update($updates);
        }
    }

    private function findPatientByWhatsAppId(string $waId): ?Paciente
    {
        return Paciente::query()
            ->whereNotNull('telefono')
            ->get()
            ->first(fn (Paciente $patient): bool => $this->whatsapp->normalizePhone($patient->telefono) === $waId);
    }

    private function hasOpenCustomerServiceWindow(Paciente $patient, string $waId): bool
    {
        $cutoff = now()->subHours(24);

        return WhatsAppMessage::query()
            ->where('direction', 'inbound')
            ->where(function ($query) use ($patient, $waId): void {
                $query->where('paciente_id', $patient->id)
                    ->orWhere('wa_id', $waId);
            })
            ->where(function ($query) use ($cutoff): void {
                $query->where('sent_at', '>=', $cutoff)
                    ->orWhere(function ($query) use ($cutoff): void {
                        $query->whereNull('sent_at')
                            ->where('created_at', '>=', $cutoff);
                    });
            })
            ->exists();
    }

    private function sendAutomaticReplyWhenNeeded(WhatsAppMessage $incoming): void
    {
        if (! config('services.whatsapp.auto_reply_enabled', false) || $incoming->type !== 'text') {
            return;
        }

        $body = Str::lower(Str::ascii(trim((string) $incoming->body)));
        $isGreeting = preg_match(
            '/^(hola|holi|buen dia|buenos dias|buenas tardes|buenas noches|ayuda)\b/',
            $body,
        ) === 1;

        if (! $isGreeting) {
            return;
        }

        $cooldownHours = max(1, (int) config('services.whatsapp.auto_reply_cooldown_hours', 24));
        $alreadyReplied = WhatsAppMessage::query()
            ->where('wa_id', $incoming->wa_id)
            ->where('direction', 'outbound')
            ->where('type', 'auto_reply')
            ->where('created_at', '>=', now()->subHours($cooldownHours))
            ->exists();

        if ($alreadyReplied) {
            return;
        }

        $replyBody = trim((string) config('services.whatsapp.auto_reply_message'));

        if ($replyBody === '') {
            return;
        }

        $reply = WhatsAppMessage::create([
            'paciente_id' => $incoming->paciente_id,
            'wa_id' => $incoming->wa_id,
            'direction' => 'outbound',
            'type' => 'auto_reply',
            'body' => $replyBody,
            'status' => 'pending',
        ]);

        try {
            $result = $this->whatsapp->sendText($incoming->wa_id, $replyBody);

            $reply->update([
                'meta_message_id' => $result['message_id'],
                'status' => 'accepted',
                'sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $reply->update([
                'status' => 'failed',
                'error' => Str::limit($exception->getMessage(), 1000),
                'failed_at' => now(),
            ]);

            report($exception);
        }
    }

    private function maskPhone(?string $phone): string
    {
        $normalized = $this->whatsapp->normalizePhone($phone);

        return $normalized !== ''
            ? '•••• '.substr($normalized, -4)
            : 'Sin número';
    }
}
