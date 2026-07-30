<?php

namespace Tests\Feature;

use App\Models\Clinica;
use App\Models\Estudio;
use App\Models\Paciente;
use App\Models\Reporte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PatientRecordPdfDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_record_pdf_download_returns_a_pdf_file(): void
    {
        $clinica = Clinica::create([
            'nombre' => 'Clinica Test',
            'is_shared' => false,
        ]);

        $user = User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor Test',
            'email' => 'doctor-pdf@example.com',
            'password' => Hash::make('SecurePassword1'),
            'subscription_status' => 'active',
        ]);

        $paciente = Paciente::create([
            'clinica_id' => $clinica->id,
            'folio' => 'P-007',
            'nombre_completo' => 'Alan Perez',
            'telefono' => '48956456944',
            'procedimiento' => 'Endoscopia',
            'diagnostico_preliminar' => 'Revision general',
        ]);

        $study = Estudio::create([
            'clinica_id' => $clinica->id,
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => 'E-005',
            'tipo' => 'Endoscopia',
            'fecha' => '2026-07-27',
            'estado' => 'completado',
            'diagnostico' => 'Gastritis leve',
            'observaciones' => 'Sin complicaciones',
        ]);

        Reporte::create([
            'clinica_id' => $clinica->id,
            'estudio_id' => $study->id,
            'usuario_id' => $user->id,
            'contenido_texto' => 'Reporte clinico del expediente.',
            'contiene_hallazgos_criticos' => false,
        ]);

        $response = $this->actingAs($user)->get(route('pacientes.expediente.pdf', $paciente));

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');

        $contentDisposition = $response->baseResponse->headers->get('Content-Disposition');
        $content = $response->baseResponse->getContent();

        $this->assertStringContainsString('attachment; filename="expediente-', (string) $contentDisposition);
        $this->assertIsString($content);
        $this->assertStringStartsWith('%PDF-', $content);
    }
}
