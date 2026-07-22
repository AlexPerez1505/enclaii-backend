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

    public function test_evidencia_route_streams_private_study_image(): void
    {
        config([
            'filesystems.media_disk' => 's3',
            'filesystems.media_signed_urls' => false,
        ]);
        Storage::fake('s3');

        $user = User::create([
            'name' => 'Doctor Evidencias',
            'email' => 'evidencias'.uniqid().'@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);

        $paciente = Paciente::create([
            'clinica_id' => $user->clinica_id,
            'folio' => 'PX-IMG',
            'nombre_completo' => 'Paciente Imagen',
        ]);

        $estudio = Estudio::create([
            'clinica_id' => $user->clinica_id,
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'EST-IMG',
            'tipo' => 'Colonoscopia',
            'fecha' => today(),
            'estado' => 'completado',
        ]);

        $imagePath = "clinics/{$user->clinica_id}/patients/{$paciente->id}/studies/{$estudio->id}/images/captura.png";
        $image = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true,
        );
        Storage::disk('s3')->put($imagePath, $image);

        $archivo = EstudioArchivo::create([
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
            ->get(route('ia-reportes.evidencia', ['archivo' => $archivo->id]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $this->assertSame($image, $response->streamedContent());
    }

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

    public function test_guardar_reporte_respects_image_visibility_and_size_config(): void
    {
        config([
            'filesystems.media_disk' => 's3',
            'filesystems.media_signed_urls' => false,
        ]);
        Storage::fake('s3');

        $user = User::create([
            'name' => 'Doctor Imagenes',
            'email' => 'imagenes'.uniqid().'@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);

        $paciente = Paciente::create([
            'clinica_id' => $user->clinica_id,
            'folio' => 'PX-CFG',
            'nombre_completo' => 'Paciente Config',
        ]);

        $estudio = Estudio::create([
            'clinica_id' => $user->clinica_id,
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'EST-CFG',
            'tipo' => 'Colonoscopia',
            'fecha' => today(),
            'estado' => 'completado',
        ]);

        $image = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=',
            true,
        );

        $hiddenPath = "clinics/{$user->clinica_id}/patients/{$paciente->id}/studies/{$estudio->id}/images/oculta.png";
        $visiblePath = "clinics/{$user->clinica_id}/patients/{$paciente->id}/studies/{$estudio->id}/images/visible.png";
        Storage::disk('s3')->put($hiddenPath, $image);
        Storage::disk('s3')->put($visiblePath, $image);

        $hidden = EstudioArchivo::create([
            'clinica_id' => $user->clinica_id,
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'oculta.png',
            'nombre' => 'oculta',
            'path' => $hiddenPath,
            'mime_type' => 'image/png',
            'size_bytes' => Storage::disk('s3')->size($hiddenPath),
            'capturado_en' => now()->subMinute(),
        ]);

        $visible = EstudioArchivo::create([
            'clinica_id' => $user->clinica_id,
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'visible.png',
            'nombre' => 'visible',
            'path' => $visiblePath,
            'mime_type' => 'image/png',
            'size_bytes' => Storage::disk('s3')->size($visiblePath),
            'capturado_en' => now(),
        ]);

        $response = $this->actingAs($user)
            ->postJson(route('ia-reportes.guardar'), [
                'estudio_id' => $estudio->id,
                'contenido_texto' => 'Contenido clinico con seleccion de imagenes.',
                'contenido_html' => '<h4>HALLAZGOS</h4><p>Contenido clinico con seleccion de imagenes.</p>',
                'imagenes_config' => [
                    'version' => 1,
                    'cols' => 4,
                    'items' => [
                        (string) $hidden->id => ['visible' => false, 'size' => 1],
                        (string) $visible->id => ['visible' => true, 'size' => 2],
                    ],
                ],
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $reporteId = $response->json('reporte_id');
        $html = Storage::disk('s3')->get($estudio->fresh()->reporte_path);

        $this->assertDatabaseHas('reportes', [
            'id' => $reporteId,
            'estudio_id' => $estudio->id,
        ]);
        $this->assertStringContainsString('visible.png', $html);
        $this->assertStringContainsString('grid-column:span 2', $html);
        $this->assertStringNotContainsString('oculta.png', $html);
    }
}
