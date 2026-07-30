<?php

namespace Tests\Feature;

use App\Mail\StudyShareMail;
use App\Models\Clinica;
use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use App\Models\Reporte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PatientStudyHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_history_includes_links_for_all_studies(): void
    {
        [$clinica, $user] = $this->clinicWithUser();
        $paciente = $this->patient($clinica);
        $studyA = $this->study($clinica, $paciente, 'E-001', 'Endoscopia', '2026-07-10');
        $studyB = $this->study($clinica, $paciente, 'E-002', 'Colonoscopia', '2026-07-20');

        $response = $this->actingAs($user)->get('/pacientes');

        $response
            ->assertOk()
            ->assertSee('Historial de estudios', false)
            ->assertSee($this->encodedStudyUrlFragment($paciente, $studyA), false)
            ->assertSee($this->encodedStudyUrlFragment($paciente, $studyB), false)
            ->assertSee('E-001', false)
            ->assertSee('E-002', false);
    }

    public function test_study_history_link_opens_patient_study_detail(): void
    {
        [$clinica, $user] = $this->clinicWithUser();
        $paciente = $this->patient($clinica);
        $study = $this->study($clinica, $paciente, 'E-003', 'Endoscopia digestiva alta', '2026-07-25', [
            'diagnostico' => 'Gastritis leve',
            'descripcion' => 'Revision de control',
            'observaciones' => 'Sin complicaciones',
        ]);

        $response = $this->actingAs($user)
            ->get('/nuevo-estudio?paciente='.$paciente->id.'&estudio_id='.$study->id);

        $response
            ->assertOk()
            ->assertSee('Estudio del paciente', false)
            ->assertSee('Folio del estudio', false)
            ->assertSee('E-003', false)
            ->assertSee('Endoscopia digestiva alta', false)
            ->assertSee('Gastritis leve', false)
            ->assertSee('data-study-email-open', false)
            ->assertDontSee('data-tab="reportes"', false)
            ->assertDontSee('id="tab-reportes"', false);
    }

    public function test_study_share_email_sends_reports_images_and_videos_together(): void
    {
        Mail::fake();
        Storage::fake('public');
        config([
            'filesystems.media_disk' => 'public',
            'mail.from.address' => 'gmail-clinic@example.com',
            'mail.from.name' => 'ENCLAII Gmail',
        ]);

        [$clinica, $user] = $this->clinicWithUser();
        $paciente = $this->patient($clinica);
        $study = $this->study($clinica, $paciente, 'E-004', 'Endoscopia', '2026-07-26');

        Storage::disk('public')->put('studies/e004/capture.jpg', 'image-bytes');
        Storage::disk('public')->put('studies/e004/video.webm', 'video-bytes');

        EstudioArchivo::create([
            'clinica_id' => $clinica->id,
            'estudio_id' => $study->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'imagen',
            'nombre_original' => 'capture.jpg',
            'nombre' => 'capture.jpg',
            'path' => 'studies/e004/capture.jpg',
            'capturado_en' => '2026-07-26 10:00:00',
        ]);

        EstudioArchivo::create([
            'clinica_id' => $clinica->id,
            'estudio_id' => $study->id,
            'paciente_id' => $paciente->id,
            'tipo' => 'video',
            'nombre_original' => 'video.webm',
            'nombre' => 'video.webm',
            'path' => 'studies/e004/video.webm',
            'capturado_en' => '2026-07-26 10:05:00',
        ]);

        Reporte::create([
            'clinica_id' => $clinica->id,
            'estudio_id' => $study->id,
            'usuario_id' => $user->id,
            'contenido_texto' => 'Reporte clinico del estudio.',
            'contiene_hallazgos_criticos' => false,
        ]);

        $response = $this->actingAs($user)->postJson(route('nuevo-estudio.correo.send', $study), [
            'recipients' => 'paciente@example.com',
            'subject' => 'Estudio E-004',
            'message' => 'Te comparto los archivos del estudio.',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('message', 'Estudio enviado correctamente.')
            ->assertJsonPath('sent_to.0', 'paciente@example.com');

        Mail::assertSent(StudyShareMail::class, function (StudyShareMail $mail) use ($study): bool {
            return $mail->estudio->is($study)
                && $mail->reportes->count() === 1
                && $mail->imagenes->count() === 1
                && $mail->videos->count() === 1;
        });
    }

    private function clinicWithUser(): array
    {
        $clinica = Clinica::create([
            'nombre' => 'Clinica Test',
            'is_shared' => false,
        ]);

        $user = User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor Test',
            'email' => 'doctor@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        return [$clinica, $user];
    }

    private function patient(Clinica $clinica): Paciente
    {
        return Paciente::create([
            'clinica_id' => $clinica->id,
            'folio' => 'P-001',
            'nombre_completo' => 'Alan Perez',
            'procedimiento' => 'Endoscopia',
        ]);
    }

    private function study(Clinica $clinica, Paciente $paciente, string $folio, string $tipo, string $fecha, array $extra = []): Estudio
    {
        return Estudio::create(array_merge([
            'clinica_id' => $clinica->id,
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => $folio,
            'tipo' => $tipo,
            'fecha' => $fecha,
            'estado' => 'completado',
        ], $extra));
    }

    private function encodedStudyUrlFragment(Paciente $paciente, Estudio $study): string
    {
        return 'nuevo-estudio?paciente='.$paciente->id.'\u0026estudio_id='.$study->id;
    }
}
