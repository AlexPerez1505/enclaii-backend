<?php

namespace App\Http\Controllers;

use App\Models\AiConversation;
use App\Models\AiMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class SoporteChatController extends Controller
{
    public function chat(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message' => 'required|string|max:4000',
            'conversation_id' => 'nullable|integer|exists:ai_conversations,id',
        ]);

        $user = $request->user();
        $conversationId = $data['conversation_id'] ?? null;

        $conversation = $this->conversation($user->id, $conversationId);

        $userMessage = $conversation->messages()->create([
            'role' => 'user',
            'content' => $data['message'],
        ]);

        $conversation->update([
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        if ($conversation->title === 'Nuevo chat' || empty($conversation->title)) {
            $conversation->update([
                'title' => $this->makeTitle($data['message']),
            ]);
        }

        $history = $conversation->messages()
            ->get()
            ->map(fn ($m) => [
                'role' => $m->role,
                'content' => $m->content,
            ])
            ->toArray();

        try {
            $reply = $this->runConversation($history);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'reply' => 'No pude conectar con la IA en este momento. Intenta de nuevo.',
                'conversation_id' => $conversation->id,
            ], 200);
        }

        $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $reply,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'reply' => $reply,
            'conversation_id' => $conversation->id,
            'title' => $conversation->title,
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

        if (!$conversation) {
            return response()->json([
                'ok' => true,
                'conversation' => null,
                'messages' => [],
            ]);
        }

        $messages = $conversation->messages()
            ->get()
            ->map(fn ($m) => [
                'id' => $m->id,
                'role' => $m->role,
                'content' => $m->content,
                'created_at' => $m->created_at?->toDateTimeString(),
            ]);

        return response()->json([
            'ok' => true,
            'conversation' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'status' => $conversation->status,
            ],
            'messages' => $messages,
        ]);
    }

    private function conversation(int $userId, ?int $conversationId): AiConversation
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
            'user_id' => $userId,
            'type' => 'soporte',
            'title' => 'Nuevo chat',
            'status' => 'active',
            'last_message_at' => now(),
        ]);
    }

    private function runConversation(array $history): string
    {
        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            report(new \RuntimeException('OpenAI API key no configurada para SoporteChatController.'));
            return 'El asistente de IA no está configurado. Contacta al administrador o configura OPENAI_KEY en el archivo .env.';
        }

        $baseUrl = rtrim(config('services.openai.base_url', 'https://api.openai.com/v1'), '/');
        $model = config('services.openai.model', 'gpt-4o-mini');

        if (str_ends_with($baseUrl, '/v1')) {
            $baseUrl = substr($baseUrl, 0, -3);
        }

        $messages = [
            [
                'role' => 'system',
                'content' =>
                    "Eres el asistente de soporte de ENCLAII, plataforma médica de endoscopía. ".
                    "Responde siempre en español de México, breve y claro. ".
                    "Ayudas a los usuarios con problemas, dudas, facturación, funcionalidades técnicas y reportes del sistema. ".
                    "Si el problema requiere intervención humana o no puedes resolverlo, indica amablemente que se generará un ticket de soporte para que el equipo lo revise. ".
                    "No crees pacientes ni citas; ese no es tu rol. ".
                    "Usa Markdown para listas y resalta en **negrita** los pasos clave.",
            ],
            ...$history,
        ];

        try {
            $resp = Http::withToken($apiKey)
                ->baseUrl($baseUrl)
                ->timeout(90)
                ->post('/v1/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'max_completion_tokens' => 700,
                ]);
        } catch (Throwable $e) {
            report(new \RuntimeException('OpenAI request exception: '.$e->getMessage()));
            return 'No pude contactar a la IA (error de red/timeout). Revisa la conexión o los logs de Laravel.';
        }

        if ($resp->failed()) {
            $status = $resp->status();
            $body = $resp->body() ?: '[respuesta vacía]';
            report(new \RuntimeException("OpenAI error (HTTP {$status}): {$body}"));
            return "No pude contactar a la IA (HTTP {$status}). Revisa la configuración de OpenAI o los logs de Laravel.";
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
