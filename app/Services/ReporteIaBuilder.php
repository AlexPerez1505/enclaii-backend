<?php

namespace App\Services;

use App\Models\EstudioHallazgo;
use App\Models\Hallazgo;
use Illuminate\Support\Str;

/**
 * Construye el HTML y persiste los efectos secundarios (hallazgos) de un
 * reporte generado por IA. Extraído de IaReporteController para poder
 * reutilizarse tanto desde el controlador como desde GenerarReporteIaJob.
 */
class ReporteIaBuilder
{
    /**
     * Mapea un tipo de estudio a la clave de plantilla del editor.
     */
    public function tipoEstudioToKey(?string $tipo): string
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
    public function reporteIaToHtml(array $reporte): string
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

    /**
     * Persiste los hallazgos detectados por la IA en la tabla estudio_hallazgos.
     */
    public function persistirHallazgosIa(int $estudioId, array $reporte): void
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
