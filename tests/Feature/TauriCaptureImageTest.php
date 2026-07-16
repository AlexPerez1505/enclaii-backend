<?php

namespace Tests\Feature;

use App\Models\CaptureSession;
use App\Models\Estudio;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TauriCaptureImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_tauri_image_is_saved_as_study_archive_for_current_study(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Doctor Captura',
            'email' => 'captura@example.com',
            'password' => 'password',
            'clinica_id' => 1,
            'subscription_status' => 'active',
        ]);

        $paciente = Paciente::create([
            'clinica_id' => 1,
            'folio' => 'P-CAP-1',
            'nombre_completo' => 'Paciente Captura',
        ]);

        $estudioAnterior = Estudio::create([
            'clinica_id' => 1,
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-CAP-1',
            'fecha' => today(),
            'estado' => 'en_proceso',
        ]);

        $estudioActual = Estudio::create([
            'clinica_id' => 1,
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-CAP-2',
            'fecha' => today(),
            'estado' => 'en_proceso',
        ]);

        CaptureSession::create([
            'tenant_id' => 1,
            'user_id' => $user->id,
            'paciente_id' => $paciente->id,
            'estudio_id' => $estudioAnterior->id,
            'study_id' => $estudioAnterior->id,
            'status' => 'active',
            'started_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $sessionId = $this->postJson('/api/tauri/estudios/iniciar', [
            'paciente_id' => $paciente->id,
            'estudio_id' => $estudioActual->id,
        ])
            ->assertOk()
            ->json('data.session_id');

        $this->post('/api/tauri/images', [
            'session_id' => $sessionId,
            'image' => UploadedFile::fake()->image('captura.jpg', 640, 480),
        ])
            ->assertOk()
            ->assertJsonPath('data.estudio_id', $estudioActual->id);

        $this->assertDatabaseHas('estudio_archivos', [
            'estudio_id' => $estudioActual->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'categoria' => 'tauri-capture',
            'nombre_original' => 'captura.jpg',
        ]);

        $this->assertDatabaseMissing('estudio_archivos', [
            'estudio_id' => $estudioAnterior->id,
            'nombre_original' => 'captura.jpg',
        ]);
    }
}
