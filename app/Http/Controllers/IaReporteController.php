<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use App\Services\OpenAiReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class IaReporteController extends Controller
{
    public function generar(Request $request, OpenAiReportService $service): JsonResponse
    {
        $validated = $request->validate([
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

        return response()->json([
            'ok' => true,
            'reporte' => $reporte,
        ]);
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
            'contenido_texto' => ['required', 'string'],
            'contiene_hallazgos_criticos' => ['nullable', 'boolean'],
        ]);

        $data = [
            'estudio_id' => $validated['estudio_id'],
            'usuario_id' => Auth::id(),
            'contenido_texto' => $validated['contenido_texto'],
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
}
