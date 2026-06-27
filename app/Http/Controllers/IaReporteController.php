<?php

namespace App\Http\Controllers;

use App\Models\EstudioHallazgo;
use App\Models\Hallazgo;
use App\Models\Plantilla;
use App\Models\Reporte;
use App\Services\OpenAiReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class IaReporteController extends Controller
{
    public function generar(Request $request, OpenAiReportService $service): JsonResponse
    {
        $validated = $request->validate([
            'estudio_id' => ['required', 'exists:estudios,id'],
            'paciente' => ['nullable', 'string', 'max:255'],
            'tipo_estudio' => ['nullable', 'string', 'max:255'],
            'fecha' => ['nullable', 'string', 'max:50'],
            'observaciones' => ['required', 'string', 'max:5000'],
            'opciones' => ['nullable', 'array'],
            'imagenes' => ['nullable', 'array', 'max:8'],
            'imagenes.*' => ['string', 'max:255'],
        ]);

        $validated['paciente'] = $validated['paciente'] ?? 'No especificado';
        $validated['tipo_estudio'] = $validated['tipo_estudio'] ?? 'Estudio endoscópico';
        $validated['fecha'] = $validated['fecha'] ?? now()->toDateString();
        $validated['imagenes'] = $this->resolverImagenes($validated['imagenes'] ?? []);

        try {
            $reporte = $service->generarReporte($validated);
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        // Persistir hallazgos detectados por la IA en estudio_hallazgos.
        $this->persistirHallazgosIa($validated['estudio_id'], $reporte);

        // Persistir el reporte preliminar en la BD para conservar formato e imágenes.
        $tipoKey = $this->tipoEstudioToKey($validated['tipo_estudio']);
        $plantillaId = Plantilla::where('clave', $tipoKey)->value('id');
        $html = $this->reporteIaToHtml($reporte);
        $nuevo = Reporte::create([
            'estudio_id' => $validated['estudio_id'],
            'usuario_id' => Auth::id(),
            'plantilla_id' => $plantillaId,
            'contenido_texto' => strip_tags($html),
            'contenido_html' => $html,
            'contiene_hallazgos_criticos' => false,
        ]);

        return response()->json([
            'ok' => true,
            'reporte' => $reporte,
            'reporte_id' => $nuevo->id,
        ]);
    }

    /**
     * Muestra el dashboard de hallazgos con datos reales de la base de datos.
     */
    public function hallazgos(): View
    {
        $data = $this->hallazgosData();

        return view('ia-reportes.hallazgos', [
            'hallazgos' => $data['hallazgos'],
            'totalHallazgos' => $data['totalHallazgos'],
            'totalEstudios' => $data['totalEstudios'],
            'totalCriticos' => $data['totalCriticos'],
            'hallazgoPrincipal' => $data['hallazgoPrincipal'],
        ]);
    }

    /**
     * Devuelve los hallazgos agregados para dashboards y reportes.
     */
    public function hallazgosData(): array
    {
        $raw = EstudioHallazgo::with(['hallazgo', 'estudio.paciente'])->get();

        $totalHallazgos = $raw->count();

        $hallazgos = $raw
            ->groupBy('hallazgo_id')
            ->map(function ($items) {
                $hallazgo = $items->first()->hallazgo;
                $pacientes = $items
                    ->map(fn ($item) => $item->estudio?->paciente)
                    ->filter()
                    ->unique('id')
                    ->values();

                return [
                    'id' => $hallazgo->id,
                    'nombre' => $hallazgo->nombre,
                    'cantidad' => $items->count(),
                    'porcentaje' => 0,
                    'pacientes' => $pacientes,
                    'es_critico' => (bool) $hallazgo->es_critico,
                ];
            })
            ->sortByDesc('cantidad')
            ->values()
            ->all();

        $maxCantidad = collect($hallazgos)->max('cantidad') ?: 1;
        foreach ($hallazgos as $key => $h) {
            $hallazgos[$key]['porcentaje'] = round($h['cantidad'] / $maxCantidad * 100);
        }

        $totalEstudios = \App\Models\Estudio::count();
        $totalCriticos = collect($hallazgos)->where('es_critico', true)->sum('cantidad');
        $hallazgoPrincipal = $hallazgos[0]['nombre'] ?? 'Ninguno';

        return [
            'hallazgos' => $hallazgos,
            'totalHallazgos' => $totalHallazgos,
            'totalEstudios' => $totalEstudios,
            'totalCriticos' => $totalCriticos,
            'hallazgoPrincipal' => $hallazgoPrincipal,
        ];
    }

    /**
     * Mapea un tipo de estudio a la clave de plantilla del editor.
     */
    private function tipoEstudioToKey(?string $tipo): string
    {
        $t = Str::lower($tipo ?? '');
        if (Str::contains($t, 'colono')) return 'colonoscopia';
        if (Str::contains($t, 'gastro')) return 'gastroscopia';
        if (Str::contains($t, 'duodeno')) return 'duodenoscopia';
        if (Str::contains($t, 'bronco')) return 'broncoscopia';
        return 'blanco';
    }

    /**
     * Convierte el reporte estructurado de la IA en HTML con secciones y anexo de imágenes.
     */
    private function reporteIaToHtml(array $reporte): string
    {
        $inf = $reporte['informe'] ?? [];
        $secciones = [
            'INDICACIÓN' => $inf['indicacion'] ?? '',
            'SEDACIÓN' => $inf['sedacion'] ?? '',
            'HALLAZGOS' => $inf['hallazgos'] ?? [],
            'IMPRESIÓN DIAGNÓSTICA' => $inf['impresion_diagnostica'] ?? '',
            'PLAN Y RECOMENDACIONES' => $inf['plan_recomendaciones'] ?? [],
            'OBSERVACIONES' => $inf['observaciones'] ?? '',
        ];

        $html = '';
        foreach ($secciones as $titulo => $contenido) {
            $html .= '<h4>' . e($titulo) . '</h4>';
            if (is_array($contenido)) {
                if (count($contenido)) {
                    $html .= '<ul>';
                    foreach ($contenido as $item) {
                        $html .= '<li>' . e($item) . '</li>';
                    }
                    $html .= '</ul>';
                }
            } elseif (trim($contenido) !== '') {
                $html .= '<p>' . e($contenido) . '</p>';
            }
        }

        $anexo = $reporte['anexo'] ?? [];
        if (count($anexo)) {
            $html .= '<h4>ANEXO DE IMÁGENES</h4>';
            foreach ($anexo as $i => $desc) {
                $html .= '<p><b>Imagen ' . ($i + 1) . ':</b> ' . e($desc) . '</p>';
            }
        }

        return $html;
    }

    public function chat(Request $request, OpenAiReportService $service): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
            'contexto' => ['nullable', 'string', 'max:8000'],
        ]);

        try {
            $resultado = $service->chat($validated['message'], $validated['contexto'] ?? '');
        } catch (Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'respuesta' => $resultado['respuesta'],
            'acciones' => $resultado['acciones'],
        ]);
    }

    /**
     * Guarda (o actualiza) un reporte clínico ligado a un estudio.
     */
    public function guardar(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'estudio_id' => ['required', 'exists:estudios,id'],
            'reporte_id' => ['nullable', 'exists:reportes,id'],
            'contenido_texto' => ['nullable', 'string'],
            'contenido_html' => ['nullable', 'string'],
            'contiene_hallazgos_criticos' => ['nullable', 'boolean'],
            'plantilla_id' => ['nullable', 'exists:plantillas,id'],
        ]);

        $data = [
            'estudio_id' => $validated['estudio_id'],
            'usuario_id' => Auth::id(),
            'plantilla_id' => $validated['plantilla_id'] ?? null,
            'contenido_texto' => $validated['contenido_texto'] ?? null,
            'contenido_html' => $validated['contenido_html'] ?? null,
            'contiene_hallazgos_criticos' => $validated['contiene_hallazgos_criticos'] ?? false,
        ];

        if (! empty($validated['reporte_id'])) {
            $reporte = Reporte::findOrFail($validated['reporte_id']);
            $reporte->update($data);
        } else {
            $reporte = Reporte::create($data);
        }

        return response()->json([
            'ok' => true,
            'reporte_id' => $reporte->id,
            'message' => 'Reporte guardado correctamente.',
        ]);
    }

    /**
     * Convierte rutas públicas de evidencia en data URLs base64 para análisis con visión.
     *
     * @param  array<int, string>  $imagenes
     * @return array<int, string>
     */
    private function resolverImagenes(array $imagenes): array
    {
        $mimes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
        ];

        return collect($imagenes)
            ->map(function (string $rel) use ($mimes) {
                // Evita rutas peligrosas (traversal) y normaliza separadores.
                $rel = str_replace('\\', '/', $rel);
                if (str_contains($rel, '..')) {
                    return null;
                }

                $path = public_path(ltrim($rel, '/'));
                if (! is_file($path)) {
                    return null;
                }

                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (! isset($mimes[$ext])) {
                    return null;
                }

                // Límite de seguridad: ~8 MB por imagen.
                if (filesize($path) > 8 * 1024 * 1024) {
                    return null;
                }

                $contents = @file_get_contents($path);
                if ($contents === false) {
                    return null;
                }

                return 'data:' . $mimes[$ext] . ';base64,' . base64_encode($contents);
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Persiste los hallazgos detectados por la IA en la tabla estudio_hallazgos.
     */
    private function persistirHallazgosIa(int $estudioId, array $reporte): void
    {
        $hallazgos = collect();

        // Hallazgos estructurados: { texto, confianza }
        foreach ($reporte['hallazgos'] ?? [] as $h) {
            $texto = trim((string) ($h['texto'] ?? ''));
            if ($texto !== '') {
                $hallazgos->push($texto);
            }
        }

        // Hallazgos del informe: array de strings
        foreach ($reporte['informe']['hallazgos'] ?? [] as $texto) {
            $texto = trim((string) $texto);
            if ($texto !== '') {
                $hallazgos->push($texto);
            }
        }

        $hallazgos = $hallazgos->unique()->values();
        if ($hallazgos->isEmpty()) {
            return;
        }

        foreach ($hallazgos as $nombre) {
            $hallazgo = Hallazgo::firstOrCreate(
                ['nombre' => $nombre],
                ['es_critico' => false]
            );

            EstudioHallazgo::updateOrCreate(
                [
                    'estudio_id' => $estudioId,
                    'hallazgo_id' => $hallazgo->id,
                ],
                [
                    'detectado_por' => 'ia',
                ]
            );
        }
    }
}
