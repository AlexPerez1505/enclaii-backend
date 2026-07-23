<?php

namespace Tests\Unit;

use App\Services\OpenAiReportService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenAiReportServiceTest extends TestCase
{
    public function test_report_service_adds_v1_to_default_openai_base_url(): void
    {
        config([
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-4o-mini',
            'services.openai.base_url' => 'https://api.openai.com',
        ]);

        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'diagnostico' => 'Sin datos patologicos concluyentes',
                            'confianza' => 80,
                            'nivel_riesgo' => 'Bajo',
                            'hallazgos' => [],
                            'recomendaciones' => [],
                            'resumen' => 'Reporte preliminar.',
                            'informe' => [
                                'indicacion' => 'Revision.',
                                'sedacion' => 'No especificada.',
                                'hallazgos' => [],
                                'impresion_diagnostica' => 'Sin datos patologicos concluyentes.',
                                'plan_recomendaciones' => [],
                                'observaciones' => '',
                            ],
                            'anexo' => [],
                        ]),
                    ],
                ]],
            ], 200),
        ]);

        $service = new OpenAiReportService;

        $report = $service->generarReporte([
            'paciente' => 'Paciente Demo',
            'tipo_estudio' => 'Colonoscopia',
            'fecha' => '2026-07-22',
            'observaciones' => 'Observaciones clinicas de prueba.',
            'opciones' => [],
        ]);

        $this->assertSame('Sin datos patologicos concluyentes', $report['diagnostico']);

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://api.openai.com/v1/chat/completions'
                && $request['model'] === 'gpt-4o-mini';
        });
    }
}
