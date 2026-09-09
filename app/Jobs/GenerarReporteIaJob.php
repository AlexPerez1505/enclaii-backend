<?php

namespace App\Jobs;

use App\Models\Plantilla;
use App\Models\Reporte;
use App\Models\SolicitudReporteIa;
use App\Services\OpenAiReportService;
use App\Services\ReporteIaBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

/**
 * Llama a OpenAI para generar el reporte preliminar de un estudio en segundo
 * plano, para no bloquear un worker de PHP-FPM durante los ~1-2 minutos que
 * puede tardar el análisis (especialmente con imágenes). El progreso se
 * consulta vía polling sobre SolicitudReporteIa (ver IaReporteController).
 */
class GenerarReporteIaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * No reintentar automáticamente: una llamada a OpenAI que falla a medias
     * (p. ej. timeout) no es segura de repetir sin intervención del usuario,
     * y evitamos duplicar el Reporte creado. El usuario puede reintentar
     * manualmente generando el reporte de nuevo desde la interfaz.
     */
    public int $tries = 1;

    /**
     * Debe ser mayor al timeout HTTP interno de OpenAiReportService (120s).
     */
    public int $timeout = 180;

    public function __construct(
        private readonly int $solicitudId,
        private readonly array $datosValidados,
    ) {}

    public function handle(OpenAiReportService $service, ReporteIaBuilder $builder): void
    {
        $solicitud = SolicitudReporteIa::withoutGlobalScopes()->find($this->solicitudId);

        if (! $solicitud || $solicitud->terminada()) {
            return;
        }

        $solicitud->forceFill(['estado' => SolicitudReporteIa::ESTADO_PROCESANDO])->save();

        try {
            $reporteIa = $service->generarReporte($this->datosValidados);

            $builder->persistirHallazgosIa($this->datosValidados['estudio_id'], $reporteIa);

            $tipoKey = $builder->tipoEstudioToKey($this->datosValidados['tipo_estudio']);
            $plantillaId = Plantilla::idForClave($tipoKey, $solicitud->clinica_id);
            $html = $builder->reporteIaToHtml($reporteIa);

            $nuevoReporte = Reporte::create([
                'clinica_id' => $solicitud->clinica_id,
                'estudio_id' => $this->datosValidados['estudio_id'],
                'usuario_id' => $solicitud->usuario_id,
                'plantilla_id' => $plantillaId,
                'contenido_texto' => strip_tags($html),
                'contenido_html' => $html,
                'contiene_hallazgos_criticos' => false,
            ]);

            $solicitud->forceFill([
                'estado' => SolicitudReporteIa::ESTADO_LISTO,
                'resultado' => $reporteIa,
                'reporte_id' => $nuevoReporte->id,
            ])->save();
        } catch (Throwable $e) {
            $solicitud->forceFill([
                'estado' => SolicitudReporteIa::ESTADO_ERROR,
                'error_mensaje' => $e->getMessage(),
            ])->save();
        }
    }
}
