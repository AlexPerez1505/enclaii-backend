<?php

namespace Tests\Feature;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CriticalSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_actions_require_a_valid_one_time_password_authorization(): void
    {
        $user = $this->user();
        $firstPatient = $this->patient('P-SEC-1');
        $secondPatient = $this->patient('P-SEC-2');

        $this->actingAs($user)
            ->deleteJson(route('pacientes.destroy', $firstPatient))
            ->assertStatus(428)
            ->assertJsonPath('code', 'critical_password_required');

        $this->postJson(route('configuracion.security.authorize'), [
            'scope' => 'patients',
            'current_password' => 'incorrecta',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('current_password');

        $token = $this->authorizeScope('patients');

        $this->withHeader('X-Critical-Authorization', $token)
            ->deleteJson(route('pacientes.destroy', $firstPatient))
            ->assertOk();

        $this->withHeader('X-Critical-Authorization', $token)
            ->deleteJson(route('pacientes.destroy', $secondPatient))
            ->assertStatus(428);

        $this->assertDatabaseHas('pacientes', ['id' => $secondPatient->id]);
    }

    public function test_user_can_update_critical_permissions_after_confirming_password(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patchJson(route('configuracion.security-settings.update'), [
                'require_password_for_studies' => false,
                'require_password_for_patients' => false,
                'audit_sensitive_actions' => false,
            ])
            ->assertStatus(428);

        $token = $this->authorizeScope('security_settings');

        $this->withHeader('X-Critical-Authorization', $token)
            ->patchJson(route('configuracion.security-settings.update'), [
                'require_password_for_studies' => false,
                'require_password_for_patients' => false,
                'audit_sensitive_actions' => false,
            ])
            ->assertOk()
            ->assertJsonPath('settings.require_password_for_patients', false)
            ->assertJsonPath('settings.require_password_for_studies', false)
            ->assertJsonPath('settings.audit_sensitive_actions', false);

        $this->assertDatabaseHas('user_security_settings', [
            'user_id' => $user->id,
            'require_password_for_studies' => false,
            'require_password_for_patients' => false,
            'audit_sensitive_actions' => false,
        ]);
    }

    public function test_disabled_patient_confirmation_allows_action_and_disabled_audit_skips_log(): void
    {
        $user = $this->user();
        $user->securitySetting()->create([
            'require_password_for_studies' => true,
            'require_password_for_patients' => false,
            'audit_sensitive_actions' => false,
        ]);
        $patient = $this->patient('P-SEC-3');

        $this->actingAs($user)
            ->deleteJson(route('pacientes.destroy', $patient))
            ->assertOk();

        $this->assertDatabaseMissing('activity_logs', [
            'user_id' => $user->id,
            'action' => 'patient_deleted',
        ]);
    }

    private function authorizeScope(string $scope): string
    {
        return $this->postJson(route('configuracion.security.authorize'), [
            'scope' => $scope,
            'current_password' => 'SecurePassword1',
        ])->assertOk()->json('token');
    }

    private function user(): User
    {
        return User::create([
            'name' => 'Doctor Seguridad',
            'email' => fake()->unique()->safeEmail(),
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);
    }

    private function patient(string $folio): Paciente
    {
        return Paciente::create([
            'folio' => $folio,
            'nombre_completo' => 'Paciente '.$folio,
        ]);
    }
}
