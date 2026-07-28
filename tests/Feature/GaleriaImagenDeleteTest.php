<?php

namespace Tests\Feature;

use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GaleriaImagenDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_gallery_groups_media_by_study(): void
    {
        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor-gallery@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-GAL-1',
            'nombre_completo' => 'Paciente Galeria',
        ]);
        $estudioUno = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-GAL-1',
            'tipo' => 'Colonoscopia',
            'fecha' => today()->subDay(),
        ]);
        $estudioDos = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-GAL-2',
            'tipo' => 'Gastroscopia',
            'fecha' => today(),
        ]);

        EstudioArchivo::create([
            'estudio_id' => $estudioUno->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'video-estudio-uno.mp4',
            'nombre' => 'video-estudio-uno',
            'path' => 'estudios/uno/video.mp4',
            'capturado_en' => now()->subHours(3),
        ]);
        EstudioArchivo::create([
            'estudio_id' => $estudioUno->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'captura-estudio-uno.jpg',
            'nombre' => 'captura-estudio-uno',
            'path' => 'estudios/uno/captura.jpg',
            'capturado_en' => now()->subHours(2),
        ]);
        EstudioArchivo::create([
            'estudio_id' => $estudioDos->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'captura-estudio-dos.jpg',
            'nombre' => 'captura-estudio-dos',
            'path' => 'estudios/dos/captura.jpg',
            'capturado_en' => now()->subHour(),
        ]);

        $this->actingAs($user)
            ->get(route('galeria.paciente', $paciente->id))
            ->assertOk()
            ->assertSee('Archivos por estudio')
            ->assertSee('Estudio E-GAL-1')
            ->assertSee('Estudio E-GAL-2')
            ->assertSee('video-estudio-uno.mp4')
            ->assertSee('captura-estudio-uno.jpg')
            ->assertSee('captura-estudio-dos.jpg');
    }

    public function test_authenticated_user_can_download_a_gallery_video(): void
    {
        $disk = media_disk();
        Storage::fake($disk);

        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor-video-download@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-VIDEO-1',
            'nombre_completo' => 'Paciente Video',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-VIDEO-1',
            'fecha' => today(),
        ]);

        $path = "estudios/{$estudio->id}/archivos/video-prueba.webm";
        Storage::disk($disk)->put($path, 'video');
        $video = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'video-prueba.webm',
            'nombre' => 'video-prueba',
            'path' => $path,
            'mime_type' => 'video/webm',
            'size_bytes' => 5,
        ]);

        $this->actingAs($user)
            ->get(route('galeria.video.archivo', $video))
            ->assertOk()
            ->assertHeader('Content-Type', 'video/webm')
            ->assertHeader('Content-Disposition', 'attachment; filename="video-prueba.webm"; filename*=UTF-8\'\'video-prueba.webm');
    }

    public function test_authenticated_user_can_delete_a_gallery_image(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-TEST-1',
            'nombre_completo' => 'Paciente Prueba',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-TEST-1',
            'fecha' => today(),
        ]);

        $path = "estudios/{$estudio->id}/archivos/prueba.jpg";
        Storage::disk('public')->put($path, 'imagen');
        $imagen = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'prueba.jpg',
            'nombre' => 'prueba',
            'path' => $path,
        ]);

        $token = $this->actingAs($user)
            ->postJson(route('configuracion.security.authorize'), [
                'scope' => 'studies',
                'current_password' => 'password',
            ])
            ->assertOk()
            ->json('token');

        $this->withHeader('X-Critical-Authorization', $token)
            ->deleteJson(route('galeria.imagen.destroy', $imagen))
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertDatabaseMissing('estudio_archivos', ['id' => $imagen->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_gallery_image_route_rejects_videos(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor2@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ]);
        $paciente = Paciente::create([
            'folio' => 'P-TEST-2',
            'nombre_completo' => 'Paciente Prueba',
        ]);
        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-TEST-2',
            'fecha' => today(),
        ]);
        $video = EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'prueba.mp4',
            'nombre' => 'prueba',
            'path' => "estudios/{$estudio->id}/archivos/prueba.mp4",
        ]);

        $token = $this->actingAs($user)
            ->postJson(route('configuracion.security.authorize'), [
                'scope' => 'studies',
                'current_password' => 'password',
            ])
            ->assertOk()
            ->json('token');

        $this->withHeader('X-Critical-Authorization', $token)
            ->deleteJson(route('galeria.imagen.destroy', $video))
            ->assertNotFound();

        $this->assertDatabaseHas('estudio_archivos', ['id' => $video->id]);
    }
}
