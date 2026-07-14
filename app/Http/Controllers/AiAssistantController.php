<?php

namespace App\Http\Controllers;

use App\Models\AiAttachment;
use App\Models\AiConversation;
use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AiAssistantController extends Controller
{
    /* ============================================================
     |  Crea un NUEVO chat cada vez que se abre el asistente
     |============================================================ */
    public function start(Request $request)
    {
        $user = $request->user();

        /*
         | Reglas de continuidad:
         | - Si el usuario abrió/usó un chat hace menos de 2 horas,
         |   se reutiliza ese mismo chat.
         | - Si ya pasaron 2 horas o más sin actividad,
         |   se cierra el anterior y se crea uno nuevo.
         */
        $limit = now()->subHours(2);

        $conversation = AiConversation::where('user_id', $user->id)
            ->where(function ($query) use ($limit) {
                $query->where('last_message_at', '>=', $limit)
                    ->orWhere(function ($q) use ($limit) {
                        $q->whereNull('last_message_at')
                            ->where('created_at', '>=', $limit);
                    });
            })
            ->orderByRaw('COALESCE(last_message_at, created_at) DESC')
            ->first();

        if ($conversation) {
            AiConversation::where('user_id', $user->id)
                ->where('status', 'active')
                ->where('id', '!=', $conversation->id)
                ->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                ]);

            $conversation->update([
                'status' => 'active',
                'closed_at' => null,
            ]);

            $messages = $conversation->messages()
                ->with('attachments')
                ->get()
                ->map(fn ($m) => [
                    'role' => $m->role,
                    'content' => $m->content,
                    'attachments' => $m->attachments->map(fn ($a) => [
                        'name' => $a->original_name,
                        'mime_type' => $a->mime_type,
                        'url' => media_url($a->path),
                    ])->values(),
                ]);

            return response()->json([
                'ok' => true,
                'reused' => true,
                'conversation' => [
                    'id' => $conversation->id,
                    'title' => $conversation->title,
                    'status' => $conversation->status,
                ],
                'messages' => $messages,
            ]);
        }

        AiConversation::where('user_id', $user->id)
            ->where('status', 'active')
            ->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

        $conversation = AiConversation::create([
            'user_id' => $user->id,
            'title' => 'Nuevo chat',
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'reused' => false,
            'conversation' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'status' => $conversation->status,
            ],
            'messages' => [],
        ]);
    }

    /* ============================================================
     |  Lista chats separados para historial
     |============================================================ */
    public function conversations(Request $request)
    {
        $conversations = AiConversation::with(['latestMessage'])
            ->where('user_id', $request->user()->id)
            ->latest('updated_at')
            ->limit(50)
            ->get()
            ->map(function ($conv) {
                $latest = $conv->latestMessage;

                return [
                    'id' => $conv->id,
                    'title' => $conv->title ?: 'Chat sin título',
                    'status' => $conv->status,
                    'snippet' => $latest?->content ?: 'Sin mensajes',
                    'time' => $conv->updated_at?->diffForHumans(null, true, true),
                ];
            });

        return response()->json([
            'conversations' => $conversations,
        ]);
    }

    /* ============================================================
     |  Carga mensajes de un chat específico
     |============================================================ */
    public function show(Request $request, AiConversation $conversation)
    {
        abort_unless($conversation->user_id === $request->user()->id, 403);

        $messages = $conversation->messages()
            ->with('attachments')
            ->get()
            ->map(fn ($m) => [
                'role' => $m->role,
                'content' => $m->content,
                'attachments' => $m->attachments->map(fn ($a) => [
                    'name' => $a->original_name,
                    'mime_type' => $a->mime_type,
                    'url' => media_url($a->path),
                ]),
            ]);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'status' => $conversation->status,
            ],
            'messages' => $messages,
        ]);
    }

    /* ============================================================
     |  Compatibilidad con endpoint anterior
     |============================================================ */
    public function history(Request $request)
    {
        $conv = AiConversation::where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if (!$conv) {
            return response()->json(['messages' => []]);
        }

        $messages = $conv->messages()
            ->with('attachments')
            ->get()
            ->map(fn ($m) => [
                'role' => $m->role,
                'content' => $m->content,
                'attachments' => $m->attachments->map(fn ($a) => [
                    'name' => $a->original_name,
                    'mime_type' => $a->mime_type,
                    'url' => media_url($a->path),
                ]),
            ]);

        return response()->json([
            'conversation_id' => $conv->id,
            'messages' => $messages,
        ]);
    }

    /* ============================================================
     |  Reinicia el chat activo o el chat enviado
     |============================================================ */
    public function reset(Request $request)
    {
        $conversationId = $request->integer('conversation_id');

        $conv = AiConversation::where('user_id', $request->user()->id)
            ->when($conversationId, fn ($q) => $q->where('id', $conversationId))
            ->when(!$conversationId, fn ($q) => $q->where('status', 'active')->latest('id'))
            ->first();

        if ($conv) {
            $conv->messages()->delete();
            $conv->update([
                'title' => 'Nuevo chat',
                'last_message_at' => now(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    /* ============================================================
     |  Chat principal con texto + imagen/video/archivo
     |============================================================ */
    public function chat(Request $request)
    {
        $data = $request->validate([
            'message' => 'nullable|string|max:4000',
            'conversation_id' => 'nullable|integer|exists:ai_conversations,id',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:25600|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,pdf,doc,docx,txt,csv,xlsx,xls',
        ]);

        if (empty($data['message']) && !$request->hasFile('attachments')) {
            return response()->json([
                'reply' => 'Escribe una pregunta o adjunta un archivo para analizarlo.',
            ], 422);
        }

        $conv = $this->conversation($request, $data['conversation_id'] ?? null);

        $messageText = $data['message'] ?? 'Analiza este archivo y dime para qué sirve dentro del sistema.';

        $userMessage = $conv->messages()->create([
            'role' => 'user',
            'content' => $messageText,
        ]);

        $uploadedAttachments = $this->storeAttachments($request, $userMessage->id);

        if ($conv->title === 'Nuevo chat' || empty($conv->title)) {
            $conv->update([
                'title' => $this->makeTitle($messageText),
            ]);
        }

        $conv->update([
            'status' => 'active',
            'last_message_at' => now(),
        ]);

        $history = $conv->messages()
            ->with('attachments')
            ->get()
            ->map(function ($m) {
                return [
                    'role' => $m->role,
                    'content' => $m->content,
                    'attachments' => $m->attachments->toArray(),
                ];
            })
            ->toArray();

        $messages = $this->buildOpenAiMessages($history);

        try {
            $reply = $this->runConversation($messages, $request);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'reply' => 'No pude conectar con la IA en este momento. Revisa los logs de Laravel.',
                'conversation_id' => $conv->id,
            ], 200);
        }

        $conv->messages()->create([
            'role' => 'assistant',
            'content' => $reply,
        ]);

        $conv->update([
            'last_message_at' => now(),
        ]);

        return response()->json([
            'reply' => $reply,
            'conversation_id' => $conv->id,
            'title' => $conv->title,
            'attachments_count' => count($uploadedAttachments),
        ]);
    }

    /* ============================================================
     |  Guarda adjuntos en el disco de medios configurado.
     |============================================================ */
    private function storeAttachments(Request $request, int $messageId): array
    {
        $saved = [];

        foreach ($request->file('attachments', []) as $file) {
            $path = media_store($file, 'clinicas/'.$request->user()->clinica_id.'/ai_uploads/'.now()->format('Y/m'));

            $attachment = AiAttachment::create([
                'ai_message_id' => $messageId,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            $saved[] = $attachment;
        }

        return $saved;
    }

    /* ============================================================
     |  Construye mensajes para OpenAI, incluyendo imágenes base64
     |============================================================ */
    private function buildOpenAiMessages(array $history): array
    {
        $messages = [$this->systemPrompt()];

        foreach ($history as $item) {
            if ($item['role'] === 'assistant') {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $item['content'],
                ];

                continue;
            }

            $content = [
                [
                    'type' => 'text',
                    'text' => $this->buildUserTextWithAttachmentContext($item['content'], $item['attachments'] ?? []),
                ],
            ];

            foreach (($item['attachments'] ?? []) as $attachment) {
                if ($this->isImage($attachment['mime_type'] ?? null)) {
                    $dataUrl = $this->attachmentToBase64DataUrl($attachment);

                    if ($dataUrl) {
                        $content[] = [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $dataUrl,
                            ],
                        ];
                    }
                }
            }

            $messages[] = [
                'role' => 'user',
                'content' => $content,
            ];
        }

        return $messages;
    }

    private function buildUserTextWithAttachmentContext(string $text, array $attachments): string
    {
        if (empty($attachments)) {
            return $text;
        }

        $lines = [];
        $lines[] = $text;
        $lines[] = '';
        $lines[] = 'Archivos adjuntos del usuario:';

        foreach ($attachments as $attachment) {
            $mime = $attachment['mime_type'] ?? 'desconocido';
            $name = $attachment['original_name'] ?? 'archivo';

            if ($this->isImage($mime)) {
                $lines[] = "- Imagen: {$name}. Analízala visualmente y responde en contexto del sistema ENCLAII.";
            } elseif (Str::startsWith($mime, 'video/')) {
                $lines[] = "- Video: {$name}. El archivo fue recibido. Si no puedes analizar el video completo, explica que se requieren fotogramas o una captura representativa.";
            } else {
                $lines[] = "- Archivo: {$name} ({$mime}). Si no puedes leerlo directamente, explica qué información necesitas o qué se podría extraer.";
            }
        }

        $lines[] = '';
        $lines[] = 'Cuando el archivo sea una captura del sistema, explica qué se ve, para qué sirve esa pantalla y qué acción puede realizar el usuario.';

        return implode("\n", $lines);
    }

    private function attachmentToBase64DataUrl(array|AiAttachment $attachment): ?string
    {
        $path = is_array($attachment) ? ($attachment['path'] ?? null) : $attachment->path;
        $mime = is_array($attachment) ? ($attachment['mime_type'] ?? null) : $attachment->mime_type;

        if (!$path || !$mime) {
            return null;
        }

        if (! media_exists($path)) {
            return null;
        }

        $base64 = base64_encode(Storage::disk(media_disk())->get($path));

        return 'data:'.$mime.';base64,'.$base64;
    }

    private function isImage(?string $mime): bool
    {
        return $mime && Str::startsWith($mime, 'image/');
    }

    /* ============================================================
     |  Llama a OpenAI
     |============================================================ */
    private function runConversation(array $messages, Request $request): string
    {
        $baseUrl = rtrim(config('services.openai.base_url', 'https://api.openai.com'), '/');
        $model = config('services.openai.assistant_model', config('services.openai.model', 'gpt-4o-mini'));

        for ($i = 0; $i < 4; $i++) {
            $resp = Http::withToken(config('services.openai.key'))
                ->baseUrl($baseUrl)
                ->timeout(90)
                ->post('/v1/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'tools' => $this->tools(),
                    'max_completion_tokens' => 700,
                ]);

            if ($resp->failed()) {
                report(new \RuntimeException('OpenAI error: '.$resp->body()));
                return 'Hubo un problema al contactar la IA. Revisa tu API key, modelo o logs de Laravel.';
            }

            $choice = $resp->json('choices.0.message');
            $calls = $choice['tool_calls'] ?? null;

            if (empty($calls)) {
                return trim($choice['content'] ?? 'No recibí respuesta. Intenta de nuevo.');
            }

            $messages[] = $choice;

            foreach ($calls as $call) {
                $name = $call['function']['name'] ?? '';
                $args = json_decode($call['function']['arguments'] ?? '{}', true) ?: [];

                $result = match ($name) {
                    'crear_paciente' => $this->crearPaciente($args, $request),
                    'crear_cita' => $this->crearCita($args),
                    default => ['ok' => false, 'error' => 'Función desconocida'],
                };

                $messages[] = [
                    'role' => 'tool',
                    'tool_call_id' => $call['id'],
                    'content' => json_encode($result, JSON_UNESCAPED_UNICODE),
                ];
            }
        }

        return 'No pude completar la acción. Intenta de nuevo.';
    }

    /* ============================================================
     |  Función: crear paciente real
     |============================================================ */
    private function crearPaciente(array $args, Request $request): array
    {
        $validator = validator($args, [
            'nombre_completo' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'identificacion' => 'nullable|string|max:50',
            'medico' => 'nullable|string|max:255',
            'procedimiento' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return [
                'ok' => false,
                'error' => 'Faltan datos válidos: '.implode(', ', $validator->errors()->all()),
            ];
        }

        $datos = $validator->validated();
        $datos['folio'] = 'P-'.now()->format('ymd').'-'.str_pad((string) (Paciente::count() + 1), 4, '0', STR_PAD_LEFT);

        if (!empty($datos['fecha_nacimiento'])) {
            $datos['edad'] = \Carbon\Carbon::parse($datos['fecha_nacimiento'])->age;
        }

        $paciente = Paciente::create($datos);

        return [
            'ok' => true,
            'id' => $paciente->id,
            'folio' => $paciente->folio,
            'mensaje' => 'Paciente creado correctamente.',
        ];
    }

    /* ============================================================
     |  Función: crear cita real
     |============================================================ */
    private function crearCita(array $args): array
    {
        $validator = validator($args, [
            'paciente_nombre' => 'required|string|max:255',
            'procedimiento' => 'required|string|max:255',
            'fecha' => 'required|date',
            'hora' => 'required|string|max:20',
            'sala' => 'nullable|string|max:100',
            'notas' => 'nullable|string|max:1000',
            'paciente_id' => [
                'nullable',
                'integer',
                Rule::exists('pacientes', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
        ]);

        if ($validator->fails()) {
            return [
                'ok' => false,
                'error' => 'Faltan datos válidos: '.implode(', ', $validator->errors()->all()),
            ];
        }

        $datos = $validator->validated();
        $datos['estado'] = 'en_espera';

        if (empty($datos['paciente_id'])) {
            $p = Paciente::where('nombre_completo', $datos['paciente_nombre'])->first();

            if ($p) {
                $datos['paciente_id'] = $p->id;
            }
        }

        $cita = Cita::create($datos);

        return [
            'ok' => true,
            'id' => $cita->id,
            'mensaje' => 'Cita creada correctamente.',
        ];
    }

    private function tools(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'crear_paciente',
                    'description' => 'Crea un nuevo paciente en el sistema. Úsala SOLO cuando el usuario confirme explícitamente que se registre.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'nombre_completo' => ['type' => 'string'],
                            'fecha_nacimiento' => ['type' => 'string'],
                            'sexo' => ['type' => 'string'],
                            'telefono' => ['type' => 'string'],
                            'email' => ['type' => 'string'],
                            'identificacion' => ['type' => 'string'],
                            'medico' => ['type' => 'string'],
                            'procedimiento' => ['type' => 'string'],
                        ],
                        'required' => ['nombre_completo'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'crear_cita',
                    'description' => 'Crea una nueva cita en la agenda. Úsala SOLO cuando el usuario confirme explícitamente que se agende.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'paciente_nombre' => ['type' => 'string'],
                            'paciente_id' => ['type' => 'integer'],
                            'procedimiento' => ['type' => 'string'],
                            'fecha' => ['type' => 'string'],
                            'hora' => ['type' => 'string'],
                            'sala' => ['type' => 'string'],
                            'notas' => ['type' => 'string'],
                        ],
                        'required' => ['paciente_nombre', 'procedimiento', 'fecha', 'hora'],
                    ],
                ],
            ],
        ];
    }

    private function systemPrompt(): array
    {
        return [
            'role' => 'system',
            'content' =>
                "Eres el asistente de ENCLAII, plataforma médica de endoscopía. ".
                "Ayudas con agenda, pacientes, reportes, capturas de pantalla y dudas del sistema. ".
                "Responde siempre en español de México, breve y claro.\n\n".

                "Si el usuario adjunta una captura del sistema, analiza visualmente la pantalla y explica:\n".
                "1. **Qué pantalla es o qué módulo parece ser**.\n".
                "2. **Para qué sirve**.\n".
                "3. **Qué puede hacer el usuario ahí**.\n".
                "4. Si detectas un error visual o funcional, da una recomendación breve.\n\n".

                "FORMATO DE RESPUESTA:\n".
                "- Usa Markdown.\n".
                "- Para listas usa lista numerada.\n".
                "- Resalta en **negrita** los datos clave.\n".
                "- No uses tablas ni encabezados con #.\n\n".

                "CREAR PACIENTE:\n".
                "Datos obligatorios: **Nombre completo**. ".
                "Recomendados: **Fecha de nacimiento**, **Sexo**, **Teléfono**, **Correo**.\n".
                "- Si faltan datos, pide SOLO los que falten y espera respuesta.\n".
                "- SOLO cuando el usuario confirme, llama a crear_paciente.\n\n".

                "AGENDAR CITA:\n".
                "Datos obligatorios: **Paciente**, **Fecha**, **Hora**, **Médico/Procedimiento**, **Sede o consultorio**.\n".
                "- SOLO cuando el usuario confirme, llama a crear_cita.",
        ];
    }

    private function conversation(Request $request, ?int $conversationId = null): AiConversation
    {
        $user = $request->user();

        if ($conversationId) {
            $conv = AiConversation::where('user_id', $user->id)
                ->where('id', $conversationId)
                ->first();

            if ($conv) {
                return $conv;
            }
        }

        $conv = AiConversation::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if ($conv) {
            return $conv;
        }

        return AiConversation::create([
            'user_id' => $user->id,
            'title' => 'Nuevo chat',
            'status' => 'active',
            'last_message_at' => now(),
        ]);
    }

    private function makeTitle(string $message): string
    {
        $message = trim(preg_replace('/\s+/', ' ', $message));

        if (mb_strlen($message) > 60) {
            return mb_substr($message, 0, 60).'...';
        }

        return $message ?: 'Nuevo chat';
    }
}
