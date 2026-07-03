<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class SoporteChatController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'history' => ['nullable', 'array', 'max:20'],
            'history.*.role' => ['required', 'string', 'in:user,assistant'],
            'history.*.content' => ['required', 'string', 'max:4000'],
        ]);

        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            return response()->json([
                'ok' => false,
                'message' => 'El servicio de IA no esta configurado.',
            ], 503);
        }

        $model = 'gpt-4.1-mini';
        $baseUrl = rtrim(config('services.openai.base_url', 'https://api.openai.com/v1'), '/');

        $messages = [[
            'role' => 'system',
            'content' => 'Eres el asistente de soporte de ENCLAII, una plataforma medica de endoscopia. '
                . 'Ayudas a los usuarios con dudas sobre la plataforma: como crear estudios, generar reportes con IA, '
                . 'gestionar pacientes, exportar datos, problemas tecnicos, facturacion y configuracion de cuenta. '
                . 'Responde en espanol, de forma clara, concisa y amable. '
                . 'Si no puedes resolver el problema, sugiere al usuario crear un ticket de soporte. '
                . 'Nunca inventes funcionalidades que no existan.',
        ]];

        $history = $validated['history'] ?? [];
        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $validated['message']];

        try {
            $response = Http::withToken($apiKey)
                ->withoutVerifying()
                ->withOptions(['verify' => false])
                ->timeout(30)
                ->acceptJson()
                ->post("{$baseUrl}/chat/completions", [
                    'model' => $model,
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                    'messages' => $messages,
                ]);

            if ($response->failed()) {
                $msg = $response->json('error.message') ?? 'Error al contactar el servicio de IA.';
                return response()->json(['ok' => false, 'message' => $msg], 502);
            }

            $content = $response->json('choices.0.message.content');

            return response()->json([
                'ok' => true,
                'reply' => trim($content),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Error de conexion con el servicio de IA.',
            ], 500);
        }
    }
}
