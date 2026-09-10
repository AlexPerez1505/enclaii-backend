<?php

namespace Tests\Feature;

use App\Models\Estudio;
use App\Models\Paciente;
use App\Models\SolicitudReporteIa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Verifica que /ia-reportes/generar encole el trabajo (no bloquee el request)
 * y que el frontend pueda seguir el progreso vía el endpoint de estado.
 */
class GenerarReporteIaAsyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_generar_encola_y_el_polling_devuelve_el_reporte_listo(): void
    {
        $this->fakeOpenAiSuccess();

        $user = $this->doctor();
        $estudio = $this->estudio($user);

        $inicio = $this->actingAs($user)->postJson(route('ia-reportes.generar.post'), [
            'estudio_id' => $estudio->id,
            'paciente' => 'Paciente Demo',
            'tipo_estudio' => 'Colonoscopia',
            'observaciones' => 'Observaciones clínicas de prueba.',
        ]);

        // La respuesta es inmediata (202) y NO contiene el reporte generado:
        // solo el id de la solicitud, porque el trabajo corre en la cola.
        $inicio->assertStatus(202)->assertJson(['ok' => true]);
        $solicitudId = $inicio->json('solicitud_id');
        $this->assertNotNull($solicitudId);
        $this->assertArrayNotHasKey('reporte', $inicio->json());

        // QUEUE_CONNECTION=sync en testing: el job ya corrió al despachar,
        // así que el polling debe ver el resultado inmediatamente.
        $estado = $this->getJson(route('ia-reportes.generar.estado', $solicitudId));

        $estado->assertOk()->assertJson([
            'ok' => true,
            'estado' => 'listo',
        ]);
        $this->assertNotNull($estado->json('reporte_id'));
        $this->assertSame('Sin datos patológicos concluyentes', $estado->json('reporte.diagnostico'));

        $this->assertDatabaseHas('reportes', [
            'id' => $estado->json('reporte_id'),
            'estudio_id' => $estudio->id,
        ]);
    }

    public function test_generar_marca_la_solicitud_como_error_si_openai_falla(): void
    {
        config(['services.openai.key' => 'test-key']);
        Http::fake([
            'https://api.openai.com/*' => Http::response(['error' => ['message' => 'Fallo simulado']], 500),
        ]);

        $user = $this->doctor();
        $estudio = $this->estudio($user);

        $inicio = $this->actingAs($user)->postJson(route('ia-reportes.generar.post'), [
            'estudio_id' => $estudio->id,
            'observaciones' => 'Observaciones clínicas de prueba.',
        ]);
        $inicio->assertStatus(202);

        $estado = $this->getJson(route('ia-reportes.generar.estado', $inicio->json('solicitud_id')));

        $estado->assertOk()->assertJson(['ok' => true, 'estado' => 'error']);
        $this->assertStringContainsString('Fallo simulado', $estado->json('error_mensaje'));
    }

    public function test_a_clinic_cannot_poll_another_clinics_solicitud(): void
    {
        // Fixtures de la clínica "ajena" creadas ANTES de autenticar a nadie,
        // para no depender de cambiar de usuario autenticado dos veces en el
        // mismo test (los middlewares de sesión del proyecto no lo soportan
        // de forma confiable, igual que en ClinicaIsolationTest/CatalogIsolationTest).
        $userB = $this->doctor('b');
        $estudioB = $this->estudio($userB);
        $solicitudB = SolicitudReporteIa::create([
            'clinica_id' => $userB->clinica_id,
            'estudio_id' => $estudioB->id,
            'usuario_id' => $userB->id,
            'estado' => SolicitudReporteIa::ESTADO_LISTO,
            'resultado' => ['diagnostico' => 'De otra clínica'],
        ]);

        $userA = $this->doctor('a');

        $this->actingAs($userA)
            ->getJson(route('ia-reportes.generar.estado', $solicitudB->id))
            ->assertNotFound();
    }

    private function fakeOpenAiSuccess(): void
    {
        config(['services.openai.key' => 'test-key']);
        Http::fake([
            'https://api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'diagnostico' => 'Sin datos patológicos concluyentes',
                            'confianza' => 80,
                            'nivel_riesgo' => 'Bajo',
                            'hallazgos' => [['texto' => 'Mucosa normal', 'confianza' => 'Alta']],
                            'recomendaciones' => ['Control en 1 año'],
                            'resumen' => 'Reporte preliminar.',
                            'informe' => [
                                'indicacion' => 'Revisión.',
                                'sedacion' => 'No especificada.',
                                'hallazgos' => ['Mucosa normal'],
                                'impresion_diagnostica' => 'Sin datos patológicos concluyentes.',
                                'plan_recomendaciones' => ['Control en 1 año'],
                                'observaciones' => '',
                            ],
                            'anexo' => [],
                        ]),
                    ],
                ]],
            ], 200),
        ]);
    }

    private function doctor(string $suffix = ''): User
    {
        return User::create([
            'name' => 'Doctor IA '.$suffix,
            'email' => 'doctor-ia'.$suffix.uniqid().'@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);
    }

    private function estudio(User $user): Estudio
    {
        $paciente = Paciente::create([
            'clinica_id' => $user->clinica_id,
            'folio' => 'PX-'.uniqid(),
            'nombre_completo' => 'Paciente Demo',
        ]);

        return Estudio::create([
            'clinica_id' => $user->clinica_id,
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'EST-'.uniqid(),
            'tipo' => 'Colonoscopia',
            'fecha' => today(),
            'estado' => 'completado',
        ]);
    }
}
