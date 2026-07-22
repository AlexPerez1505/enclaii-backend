<?php

namespace Tests\Feature;

use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IaReporteStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guardar_reporte_writes_html_file_to_study_reports_folder(): void
    {
        config([
            'filesystems.media_disk' => 's3',
            'filesystems.media_signed_urls' => false,
        ]);
        Storage::fake('s3');

        $user = User::create([
            'name' => 'Doctor Reportes',
            'email' => 'reportes'.uniqid().'@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);

        $paciente = Paciente::create([
            'clinica_id' => $user->clinica_id,
            'folio' => 'PX-001',
            'nombre_completo' => 'Paciente Demo',
        ]);

        $estudio = Estudio::create([
            'clinica_id' => $user->clinica_id,
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'EST-001',
            'tipo' => 'Colonoscopia',
            'fecha' => today(),
            'estado' => 'completado',
        ]);

        $imagePath = "clinics/{$user->clinica_id}/patients/{$paciente->id}/studies/{$estudio->id}/images/captura.png";
        Storage::disk('s3')->put($imagePath, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true,
        ));

        EstudioArchivo::create([
            'clinica_id' => $user->clinica_id,
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'captura.png',
            'nombre' => 'captura',
            'path' => $imagePath,
            'mime_type' => 'image/png',
            'size_bytes' => Storage::disk('s3')->size($imagePath),
            'capturado_en' => now(),
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('ia-reportes.guardar'), [
                'estudio_id' => $estudio->id,
                'contenido_texto' => 'Contenido clinico del reporte.',
                'contenido_html' => '<h4>HALLAZGOS</h4><p>Contenido clinico del reporte.</p>',
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $path = $estudio->fresh()->reporte_path;

        $this->assertSame(
            "clinics/{$user->clinica_id}/patients/{$paciente->id}/studies/{$estudio->id}/reports/reporte-".$response->json('reporte_id').'.html',
            $path,
        );
        Storage::disk('s3')->assertExists($path);

        $html = Storage::disk('s3')->get($path);
        $this->assertStringContainsString('Contenido clinico del reporte.', $html);
        $this->assertStringContainsString('data:image/png;base64,', $html);
    }
}
