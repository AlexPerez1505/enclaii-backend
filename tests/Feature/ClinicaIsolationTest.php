<?php

namespace Tests\Feature;

use App\Models\Cita;
use App\Models\Clinica;
use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use App\Models\Reporte;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ClinicaIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctors_only_see_patients_from_their_clinic(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'a@example.com');
        [$clinicaB] = $this->clinicaConUsuario('Clínica B', 'b@example.com');

        $patientA = $this->paciente($clinicaA, 'P-001', 'Paciente Clínica A');
        $patientB = $this->paciente($clinicaB, 'P-001', 'Paciente Clínica B');

        $this->actingAs($userA)
            ->get(route('pacientes.index'))
            ->assertOk();

        $this->assertSame([$patientA->id], Paciente::query()->pluck('id')->all());

        $this->get(route('pacientes.edit', $patientB))->assertNotFound();
        $this->deleteJson(route('pacientes.destroy', $patientB))->assertNotFound();
    }

    public function test_new_patient_is_always_assigned_to_authenticated_users_clinic(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'a@example.com');
        [$clinicaB] = $this->clinicaConUsuario('Clínica B', 'b@example.com');

        $this->actingAs($userA)->post(route('pacientes.store'), [
            'clinica_id' => $clinicaB->id,
            'folio' => 'P-010',
            'nombre_completo' => 'Paciente Seguro',
        ])->assertRedirect();

        $this->assertDatabaseHas('pacientes', [
            'clinica_id' => $clinicaA->id,
            'folio' => 'P-010',
        ]);
        $this->assertDatabaseMissing('pacientes', [
            'clinica_id' => $clinicaB->id,
            'folio' => 'P-010',
        ]);
    }

    public function test_related_clinical_records_are_scoped_by_clinic(): void
    {
        [$clinicaA, $userA] = $this->clinicaConUsuario('Clínica A', 'a@example.com');
        [$clinicaB] = $this->clinicaConUsuario('Clínica B', 'b@example.com');

        $this->clinicalRecordSet($clinicaA, 'A');
        $this->clinicalRecordSet($clinicaB, 'B');

        $this->actingAs($userA);

        $this->assertSame(1, Paciente::count());
        $this->assertSame(1, Cita::count());
        $this->assertSame(1, Estudio::count());
        $this->assertSame(1, EstudioArchivo::count());
        $this->assertSame(1, Reporte::count());
    }

    public function test_normal_registrations_cannot_access_clinical_modules_without_a_plan(): void
    {
        $this->post(route('register.post'), [
            'name' => 'Doctora Normal Uno',
            'email' => 'normal1@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
        ])->assertRedirect(route('plan.only'));

        $sharedClinic = Clinica::query()->where('is_shared', true)->firstOrFail();
        $this->get(route('pacientes.index'))->assertRedirect(route('plan.only'));
        $this->get(route('dashboard'))->assertRedirect(route('plan.only'));
        $this->get(route('agenda'))->assertRedirect(route('plan.only'));
        $this->get(route('finanzas'))->assertRedirect(route('plan.only'));

        Auth::logout();

        $this->post(route('register.post'), [
            'name' => 'Doctor Normal Dos',
            'email' => 'normal2@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
        ])->assertRedirect(route('plan.only'));

        $this->assertSame(
            [$sharedClinic->id, $sharedClinic->id],
            User::query()
                ->whereIn('email', ['normal1@example.com', 'normal2@example.com'])
                ->orderBy('email')
                ->pluck('clinica_id')
                ->all(),
        );

    }

    public function test_clinical_routes_require_authentication(): void
    {
        $this->get(route('pacientes.index'))->assertRedirect(route('login'));
        $this->get(route('agenda'))->assertRedirect(route('login'));
    }

    private function clinicaConUsuario(string $nombre, string $email): array
    {
        $clinica = Clinica::create([
            'nombre' => $nombre,
        ]);

        $user = User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor '.$nombre,
            'email' => $email,
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        return [$clinica, $user];
    }

    private function paciente(Clinica $clinica, string $folio, string $nombre): Paciente
    {
        return Paciente::create([
            'clinica_id' => $clinica->id,
            'folio' => $folio,
            'nombre_completo' => $nombre,
        ]);
    }

    private function clinicalRecordSet(Clinica $clinica, string $suffix): void
    {
        $patient = $this->paciente($clinica, 'P-'.$suffix, 'Paciente '.$suffix);
        $appointment = Cita::create([
            'clinica_id' => $clinica->id,
            'paciente_id' => $patient->id,
            'paciente_nombre' => $patient->nombre_completo,
            'fecha' => today(),
            'hora' => '10:00',
        ]);
        $study = Estudio::create([
            'clinica_id' => $clinica->id,
            'paciente_id' => $patient->id,
            'cita_id' => $appointment->id,
            'folio' => 'E-'.$suffix,
        ]);
        EstudioArchivo::create([
            'clinica_id' => $clinica->id,
            'estudio_id' => $study->id,
            'paciente_id' => $patient->id,
            'nombre_original' => 'imagen-'.$suffix.'.jpg',
            'nombre' => 'imagen-'.$suffix,
            'path' => 'pruebas/imagen-'.$suffix.'.jpg',
        ]);
        Reporte::create([
            'clinica_id' => $clinica->id,
            'estudio_id' => $study->id,
            'usuario_id' => User::query()->where('clinica_id', $clinica->id)->value('id'),
        ]);
    }
}
