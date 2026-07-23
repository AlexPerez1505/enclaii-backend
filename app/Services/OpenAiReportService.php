<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenAiReportService
{
    /**
     * Genera un reporte médico preliminar a partir de la información clínica.
     *
     * @param  array  $data  Información del estudio (paciente, tipo_estudio, fecha, observaciones, opciones).
     * @return array  Reporte estructurado.
     */
    public function generarReporte(array $data): array
    {
        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            throw new RuntimeException('No se ha configurado OPENAI_API_KEY en el archivo .env.');
        }

        $model = config('services.openai.model', 'gpt-4o-mini');
        $endpoint = $this->chatCompletionsUrl();

        $opciones = collect($data['opciones'] ?? [])->filter()->keys()->implode(', ');

        $imagenes = array_values(array_filter($data['imagenes'] ?? [], 'is_string'));
        $tieneImagenes = count($imagenes) > 0;

        $userPrompt = <<<PROMPT
        Genera un informe médico de endoscopia preliminar a partir de la siguiente información.

        Paciente: {$data['paciente']}
        Tipo de estudio: {$data['tipo_estudio']}
        Fecha del estudio: {$data['fecha']}
        Observaciones clínicas:
        {$data['observaciones']}

        Opciones de análisis solicitadas: {$opciones}

        Devuelve EXCLUSIVAMENTE un objeto JSON válido con esta estructura exacta:
        {
          "diagnostico": "string (diagnóstico preliminar conciso)",
          "confianza": number (0-100, nivel de confianza global),
          "nivel_riesgo": "Bajo" | "Moderado" | "Alto",
          "hallazgos": [{ "texto": "string", "confianza": "Alta" | "Media" | "Baja" }],
          "recomendaciones": ["string"],
          "resumen": "string (párrafo resumen clínico)",
          "informe": {
            "indicacion": "string (motivo del estudio en 1-2 frases)",
            "sedacion": "string (tipo de sedación empleada)",
            "hallazgos": ["string (cada viñeta describe un hallazgo por órgano/zona)"],
            "impresion_diagnostica": "string (impresión diagnóstica)",
            "plan_recomendaciones": ["string (cada viñeta una recomendación o plan)"],
            "observaciones": "string (observaciones adicionales, biopsias, etc.)"
          },
          "anexo": ["string (UNA descripción por cada imagen adjunta, EN EL MISMO ORDEN en que se enviaron, describiendo objetivamente lo que se observa en esa imagen)"]
        }
        PROMPT;

        if ($tieneImagenes) {
            $userPrompt .= "\n\nSe adjuntan " . count($imagenes) . ' imagen(es) endoscópica(s) como evidencia. '
                . 'Analiza visualmente cada imagen (mucosa, coloración, lesiones, erosiones, pólipos, sangrado, etc.). '
                . 'El "diagnostico" preliminar, el arreglo "hallazgos" (con su nivel de confianza) y las '
                . '"recomendaciones" DEBEN derivarse de lo que observas en las imágenes combinado con las '
                . 'observaciones clínicas; no inventes datos que no se aprecien. '
                . 'Incorpora también los hallazgos visuales a "informe.hallazgos". '
                . 'El arreglo "anexo" DEBE tener exactamente ' . count($imagenes) . ' elementos, uno por cada imagen '
                . 'en el mismo orden en que se adjuntaron; cada elemento describe objetivamente lo que se observa en esa imagen. '
                . 'Si una imagen no es interpretable o la evidencia es insuficiente, baja el nivel de confianza e indícalo con prudencia.';
        }

        // Mensaje del usuario: texto + imágenes (visión) cuando hay evidencia.
        if ($tieneImagenes) {
            $userContent = [['type' => 'text', 'text' => $userPrompt]];
            foreach ($imagenes as $img) {
                $userContent[] = [
                    'type' => 'image_url',
                    'image_url' => ['url' => $img, 'detail' => 'high'],
                ];
            }
        } else {
            $userContent = $userPrompt;
        }

        $response = Http::withToken($apiKey)
            ->timeout(120)
            ->acceptJson()
            ->post($endpoint, [
                'model' => $model,
                'temperature' => 0.3,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un asistente médico especializado en endoscopia gastrointestinal. '
                            . 'Generas reportes preliminares en español, precisos y prudentes. '
                            . 'Cuando se adjuntan imágenes, las analizas visualmente e integras los hallazgos al informe. '
                            . 'Tus respuestas son siempre sugerencias para que el profesional de la salud tome la decisión final. '
                            . 'Responde únicamente con JSON válido.',
                    ],
                    ['role' => 'user', 'content' => $userContent],
                ],
            ]);

        if ($response->failed()) {
            $msg = $response->json('error.message') ?? 'Error desconocido al contactar la API de OpenAI.';
            throw new RuntimeException("OpenAI ({$response->status()}): {$msg}");
        }

        $content = $response->json('choices.0.message.content');
        $parsed = json_decode($content, true);

        if (! is_array($parsed)) {
            throw new RuntimeException('La respuesta de la IA no tuvo un formato JSON válido.');
        }

        return $this->normalizar($parsed);
    }

    /**
     * Responde a un mensaje del usuario en el chat del editor de informes.
     *
     * @param  string  $message  Mensaje del usuario.
     * @param  string  $contexto Texto del informe actual (opcional) para dar contexto.
     */
    public function chat(string $message, string $contexto = ''): array
    {
        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            throw new RuntimeException('No se ha configurado OPENAI_API_KEY en el archivo .env.');
        }

        $model = config('services.openai.model', 'gpt-4o-mini');
        $endpoint = $this->chatCompletionsUrl();

        $messages = [[
            'role' => 'system',
            'content' => 'Eres ENCLAII, un asistente médico dentro del editor de informes de endoscopia. '
                . 'Ayudas al profesional a redactar y EDITAR el informe, resumir hallazgos, sugerir diagnósticos '
                . 'y recomendaciones, y responder preguntas. Responde en español, claro y conciso. '
                . 'Tus respuestas son sugerencias; la decisión final es del médico. '
                . "\n\nDevuelve EXCLUSIVAMENTE un objeto JSON válido con esta estructura:\n"
                . '{'
                . '"respuesta": "string (lo que le dices al usuario en el chat)",'
                . '"acciones": [{ "seccion": "indicacion|sedacion|hallazgos|impresion_diagnostica|plan_recomendaciones|observaciones", "operacion": "reemplazar|agregar", "contenido": "string o array de strings" }]'
                . "}\n"
                . 'Reglas para "acciones": inclúyelas SOLO si el usuario pide crear, cambiar, corregir, agregar o quitar contenido del informe. '
                . 'Si solo hace una pregunta o pide un resumen sin modificar el informe, deja "acciones" como []. '
                . 'Las secciones "hallazgos" y "plan_recomendaciones" usan "contenido" como array de strings (cada elemento es una viñeta). '
                . 'Las demás secciones usan "contenido" como string. '
                . '"reemplazar" sustituye toda la sección; "agregar" añade al contenido existente.',
        ]];

        if (trim($contexto) !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => "Contenido actual del informe en edición:\n" . mb_substr($contexto, 0, 4000),
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::withToken($apiKey)
            ->timeout(60)
            ->acceptJson()
            ->post($endpoint, [
                'model' => $model,
                'temperature' => 0.4,
                'response_format' => ['type' => 'json_object'],
                'messages' => $messages,
            ]);

        if ($response->failed()) {
            $msg = $response->json('error.message') ?? 'Error desconocido al contactar la API de OpenAI.';
            throw new RuntimeException("OpenAI ({$response->status()}): {$msg}");
        }

        $parsed = json_decode((string) $response->json('choices.0.message.content'), true);

        if (! is_array($parsed)) {
            return ['respuesta' => trim((string) $response->json('choices.0.message.content')), 'acciones' => []];
        }

        $seccionesValidas = ['indicacion', 'sedacion', 'hallazgos', 'impresion_diagnostica', 'plan_recomendaciones', 'observaciones'];

        $acciones = collect($parsed['acciones'] ?? [])
            ->filter(fn ($a) => is_array($a) && in_array($a['seccion'] ?? '', $seccionesValidas, true))
            ->map(fn ($a) => [
                'seccion' => (string) $a['seccion'],
                'operacion' => in_array($a['operacion'] ?? '', ['reemplazar', 'agregar'], true) ? $a['operacion'] : 'reemplazar',
                'contenido' => is_array($a['contenido'] ?? null)
                    ? array_values(array_map(fn ($x) => (string) $x, $a['contenido']))
                    : (string) ($a['contenido'] ?? ''),
            ])
            ->values()->all();

        return [
            'respuesta' => (string) ($parsed['respuesta'] ?? ''),
            'acciones' => $acciones,
        ];
    }

    private function chatCompletionsUrl(): string
    {
        $baseUrl = rtrim((string) config('services.openai.base_url', 'https://api.openai.com'), '/');

        if (str_ends_with($baseUrl, '/chat/completions')) {
            return $baseUrl;
        }

        if (str_ends_with($baseUrl, '/v1')) {
            return $baseUrl.'/chat/completions';
        }

        return $baseUrl.'/v1/chat/completions';
    }

    private function normalizar(array $r): array
    {
        $inf = is_array($r['informe'] ?? null) ? $r['informe'] : [];

        return [
            'diagnostico' => (string) ($r['diagnostico'] ?? 'Sin diagnóstico'),
            'confianza' => (int) round((float) ($r['confianza'] ?? 0)),
            'nivel_riesgo' => (string) ($r['nivel_riesgo'] ?? 'Moderado'),
            'hallazgos' => collect($r['hallazgos'] ?? [])->map(fn ($h) => [
                'texto' => (string) ($h['texto'] ?? ''),
                'confianza' => (string) ($h['confianza'] ?? 'Media'),
            ])->filter(fn ($h) => $h['texto'] !== '')->values()->all(),
            'recomendaciones' => collect($r['recomendaciones'] ?? [])
                ->map(fn ($x) => (string) $x)->filter()->values()->all(),
            'resumen' => (string) ($r['resumen'] ?? ''),
            'informe' => [
                'indicacion' => (string) ($inf['indicacion'] ?? ''),
                'sedacion' => (string) ($inf['sedacion'] ?? ''),
                'hallazgos' => collect($inf['hallazgos'] ?? [])
                    ->map(fn ($x) => (string) $x)->filter()->values()->all(),
                'impresion_diagnostica' => (string) ($inf['impresion_diagnostica'] ?? ''),
                'plan_recomendaciones' => collect($inf['plan_recomendaciones'] ?? [])
                    ->map(fn ($x) => (string) $x)->filter()->values()->all(),
                'observaciones' => (string) ($inf['observaciones'] ?? ''),
            ],
            'anexo' => collect($r['anexo'] ?? [])
                ->map(fn ($x) => (string) $x)->filter()->values()->all(),
        ];
    }
}
