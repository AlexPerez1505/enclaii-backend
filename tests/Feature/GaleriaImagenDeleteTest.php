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

    public function test_authenticated_user_can_delete_a_gallery_image(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Doctor Prueba',
            'email' => 'doctor@example.com',
            'password' => 'password',
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

        $this->actingAs($user)
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

        $this->actingAs($user)
            ->deleteJson(route('galeria.imagen.destroy', $video))
            ->assertNotFound();

        $this->assertDatabaseHas('estudio_archivos', ['id' => $video->id]);
    }
}
