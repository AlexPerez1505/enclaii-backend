<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class SoporteChatController extends Controller
{
    private const QUICK_OPTIONS = [
        'subir_estudio'      => "Para **subir un estudio** ve a **Estudios → Nuevo estudio**, selecciona al paciente y sube el archivo. Los formatos aceptados son MP4, AVI y DICOM.",
        'problema_cuenta'    => "Si tienes problemas con tu **cuenta o contraseña**, ve a **Configuración → Seguridad** y usa la opción de cambio de contraseña. Si no puedes acceder, escríbenos.",
        'facturacion'        => "Para **facturación** ve a **Configuración → Facturación**. Ahí puedes actualizar tus datos fiscales y descargar facturas emitidas.",
        'suscripcion'        => "Puedes revisar tu **suscripción y plan** en **Configuración → Plan**. Si tienes un cobro incorrecto, selecciona 'Hablar con un agente' para que te ayudemos.",
        'error_tecnico'      => "Para **errores técnicos**, intenta recargar la página. Si el problema persiste, cuéntame más detalles y te ayudo a resolverlo.",
        'hablar_agente'      => null,
    ];

    private const ESCALATION_KEYWORDS = [
        'agente', 'humano', 'persona', 'asesor', 'hablar con alguien',
        'no me ayuda', 'no funciona', 'no puedo', 'urgente', 'cancelar suscripción',
        'reembolso', 'cobro incorrecto', 'problema grave',
    ];

    public function chat(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message'         => 'required|string|max:4000',
            'conversation_id' => 'nullable|integer|exists:ai_conversations,id',
            'quick_option'    => 'nullable|string',
        ]);

        $user = $request->user();
        $conversation = $this->resolveConversation($user->id, $data['conversation_id'] ?? null);

        $conversation->update([
            'status'          => 'active',
            'last_message_at' => now(),
        ]);

        if ($conversation->title === 'Nuevo chat' || empty($conversation->title)) {
            $conversation->update(['title' => $this->makeTitle($data['message'])]);
        }

        $conversation->messages()->create([
            'role'    => 'user',
            'content' => $data['message'],
        ]);

        if ($conversation->isWithAgent()) {
            $conversation->update(['last_message_at' => now()]);
            return response()->json([
                'reply'           => null,
                'mode'            => 'with_agent',
                'conversation_id' => $conversation->id,
                'title'           => $conversation->title,
            ]);
        }

        if ($data['quick_option'] === 'hablar_agente' || $conversation->isPendingAgent()) {
            return $this->handleEscalation($conversation);
        }

        if (isset($data['quick_option']) && array_key_exists($data['quick_option'], self::QUICK_OPTIONS)) {
            $reply = self::QUICK_OPTIONS[$data['quick_option']];
            $reply .= "\n\n¿Esto resolvió tu duda? Si necesitas más ayuda puedo asistirte o conectarte con un agente.";

            $conversation->messages()->create(['role' => 'assistant', 'content' => $reply]);
            $conversation->update(['last_message_at' => now()]);

            return response()->json([
                'reply'           => $reply,
                'mode'            => $conversation->mode ?? 'bot',
                'conversation_id' => $conversation->id,
                'title'           => $conversation->title,
            ]);
        }

        if ($this->wantsAgent($data['message'])) {
            return $this->handleEscalation($conversation);
        }

        try {
            $history = $conversation->messages()->get()->map(fn ($m) => [
                'role'    => $m->role,
                'content' => $m->content,
            ])->toArray();

            $reply = $this->runConversation($history);
        } catch (Throwable $e) {
            report($e);
            $reply = 'No pude conectar con la IA en este momento. Intenta de nuevo o selecciona **Hablar con un agente**.';
        }

        $conversation->messages()->create(['role' => 'assistant', 'content' => $reply]);
        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'reply'           => $reply,
            'mode'            => $conversation->mode ?? 'bot',
            'conversation_id' => $conversation->id,
            'title'           => $conversation->title,
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $user = $request->user();

        $conversation = AiConversation::where('user_id', $user->id)
            ->where('type', 'soporte')
            ->where('status', 'active')
            ->latest('last_message_at')
            ->first();

        if (! $conversation) {
            return response()->json([
                'ok'           => true,
                'conversation' => null,
                'messages'     => [],
                'quick_options' => $this->quickOptionsList(),
            ]);
        }

        $messages = $conversation->messages()->get()->map(fn ($m) => [
            'id'         => $m->id,
            'role'       => $m->role,
            'content'    => $m->content,
            'created_at' => $m->created_at?->toDateTimeString(),
        ]);

        return response()->json([
            'ok'           => true,
            'conversation' => [
                'id'     => $conversation->id,
                'title'  => $conversation->title,
                'status' => $conversation->status,
                'mode'   => $conversation->mode ?? 'bot',
            ],
            'messages'      => $messages,
            'quick_options' => $this->quickOptionsList(),
        ]);
    }

    public function newConversation(Request $request): JsonResponse
    {
        $user = $request->user();

        AiConversation::where('user_id', $user->id)
            ->where('type', 'soporte')
            ->where('status', 'active')
            ->update(['status' => 'closed', 'closed_at' => now()]);

        $conversation = AiConversation::create([
            'user_id'         => $user->id,
            'type'            => 'soporte',
            'title'           => 'Nuevo chat',
            'status'          => 'active',
            'mode'            => 'bot',
            'last_message_at' => now(),
        ]);

        return response()->json([
            'ok'             => true,
            'conversation_id'=> $conversation->id,
        ]);
    }

    public function poll(Request $request): JsonResponse
    {
        $user = $request->user();
        $lastId = (int) $request->query('last_id', 0);
        $convId = (int) $request->query('conversation_id', 0);

        $conversation = AiConversation::where('user_id', $user->id)
            ->where('type', 'soporte')
            ->where('status', 'active')
            ->when($convId, fn ($q) => $q->where('id', $convId))
            ->latest('last_message_at')
            ->first();

        if (! $conversation) {
            return response()->json(['ok' => true, 'messages' => [], 'mode' => 'bot']);
        }

        $newMessages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->whereIn('role', ['agent', 'system'])
            ->get()
            ->map(fn ($m) => [
                'id'         => $m->id,
                'role'       => $m->role,
                'content'    => $m->content,
                'created_at' => $m->created_at?->toDateTimeString(),
            ]);

        return response()->json([
            'ok'       => true,
            'messages' => $newMessages,
            'mode'     => $conversation->mode ?? 'bot',
            'status'   => $conversation->status,
        ]);
    }

    private function handleEscalation(AiConversation $conversation): JsonResponse
    {
        if ($conversation->isBot()) {
            $conversation->requestAgent();
        }

        $reply = "Entendido. He notificado a nuestro equipo de soporte y en breve un agente se conectará contigo. "
               . "Por favor espera, normalmente respondemos en menos de 10 minutos en horario hábil.";

        $conversation->messages()->create(['role' => 'assistant', 'content' => $reply]);
        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'reply'           => $reply,
            'mode'            => $conversation->mode,
            'conversation_id' => $conversation->id,
            'title'           => $conversation->title,
            'escalated'       => true,
        ]);
    }

    private function wantsAgent(string $message): bool
    {
        $lower = mb_strtolower($message);

        foreach (self::ESCALATION_KEYWORDS as $keyword) {
            if (str_contains($lower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function quickOptionsList(): array
    {
        return [
            ['key' => 'subir_estudio',   'label' => '¿Cómo subo un estudio?'],
            ['key' => 'problema_cuenta', 'label' => 'Problema con mi cuenta'],
            ['key' => 'facturacion',     'label' => 'Facturación'],
            ['key' => 'suscripcion',     'label' => 'Mi suscripción / cobros'],
            ['key' => 'error_tecnico',   'label' => 'Error técnico'],
            ['key' => 'hablar_agente',   'label' => '💬 Hablar con un agente'],
        ];
    }

    private function resolveConversation(int $userId, ?int $conversationId): AiConversation
    {
        if ($conversationId) {
            $conv = AiConversation::where('user_id', $userId)
                ->where('type', 'soporte')
                ->where('id', $conversationId)
                ->first();

            if ($conv) {
                return $conv;
            }
        }

        $conv = AiConversation::where('user_id', $userId)
            ->where('type', 'soporte')
            ->where('status', 'active')
            ->latest('last_message_at')
            ->first();

        if ($conv) {
            return $conv;
        }

        return AiConversation::create([
            'user_id'         => $userId,
            'type'            => 'soporte',
            'title'           => 'Nuevo chat',
            'status'          => 'active',
            'mode'            => 'bot',
            'last_message_at' => now(),
        ]);
    }

    private function runConversation(array $history): string
    {
        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            return 'El asistente de IA no está configurado. Selecciona **Hablar con un agente** para que el equipo te ayude.';
        }

        $baseUrl = rtrim(config('services.openai.base_url', 'https://api.openai.com/v1'), '/');
        $model   = config('services.openai.model', 'gpt-4o-mini');

        if (str_ends_with($baseUrl, '/v1')) {
            $baseUrl = substr($baseUrl, 0, -3);
        }

        $messages = [
            [
                'role'    => 'system',
                'content' => "Eres el asistente de soporte de ENCLAII, plataforma médica de endoscopía. "
                           . "Responde siempre en español de México, breve y claro. "
                           . "Ayudas con problemas, dudas, facturación, funcionalidades técnicas y reportes del sistema. "
                           . "Si el problema requiere intervención humana o no puedes resolverlo, indica al usuario que escriba 'hablar con agente'. "
                           . "No crees pacientes ni citas; ese no es tu rol. "
                           . "Usa Markdown para listas y resalta en **negrita** los pasos clave.",
            ],
            ...$history,
        ];

        try {
            $resp = Http::withToken($apiKey)
                ->baseUrl($baseUrl)
                ->timeout(90)
                ->post('/v1/chat/completions', [
                    'model'                 => $model,
                    'messages'              => $messages,
                    'max_completion_tokens' => 700,
                ]);
        } catch (Throwable $e) {
            report(new \RuntimeException('OpenAI request exception: '.$e->getMessage()));

            return 'No pude contactar a la IA (error de red/timeout). Revisa la conexión o selecciona **Hablar con un agente**.';
        }

        if ($resp->failed()) {
            $status = $resp->status();
            $body   = $resp->body() ?: '[respuesta vacía]';
            report(new \RuntimeException("OpenAI error (HTTP {$status}): {$body}"));

            return "No pude contactar a la IA (HTTP {$status}). Selecciona **Hablar con un agente** si necesitas ayuda inmediata.";
        }

        return trim($resp->json('choices.0.message.content') ?? 'No recibí respuesta. Intenta de nuevo.');
    }

    private function makeTitle(string $message): string
    {
        $message = trim(preg_replace('/\s+/', ' ', $message));

        if (mb_strlen($message) > 60) {
            return mb_substr($message, 0, 60).'...';
        }

        return $message ?: 'Soporte';
    }
}
