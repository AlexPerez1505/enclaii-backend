<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_login_is_recorded_with_ip_and_device(): void
    {
        $user = $this->user();

        $this
            ->withServerVariables([
                'REMOTE_ADDR' => '192.0.2.25',
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0) Chrome/124.0',
            ])
            ->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'SecurePassword1',
            ])
            ->assertRedirect();

        $log = ActivityLog::query()->firstOrFail();

        $this->assertSame($user->id, $log->user_id);
        $this->assertSame('login', $log->action);
        $this->assertSame('authentication', $log->category);
        $this->assertSame('192.0.2.25', $log->ip_address);
        $this->assertSame('Windows · Chrome', $log->deviceLabel());
    }

    public function test_patient_creation_is_recorded_without_patient_name(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->postJson(route('pacientes.store'), [
                'folio' => 'P-AUDIT-1',
                'nombre_completo' => 'Nombre Clínico Confidencial',
            ])
            ->assertSuccessful();

        $log = ActivityLog::query()
            ->where('action', 'patient_created')
            ->firstOrFail();

        $this->assertStringContainsString('P-AUDIT-1', $log->description);
        $this->assertStringNotContainsString('Nombre Clínico Confidencial', $log->description);
        $this->assertSame('patients', $log->category);
    }

    public function test_security_page_searches_and_paginates_real_activity(): void
    {
        $user = $this->user();
        $logger = app(ActivityLogger::class);

        foreach (range(1, 9) as $number) {
            $logger->record(
                'test_action_'.$number,
                'security',
                $number === 1 ? 'Cambió su firma digital' : 'Evento de seguridad '.$number,
                user: $user,
            );
        }

        $this->actingAs($user)
            ->get(route('configuracion', ['tab' => 'seguridad']))
            ->assertOk()
            ->assertSee('Página 1 de 2')
            ->assertSee('Evento de seguridad 9');

        $this->actingAs($user)
            ->get(route('configuracion', [
                'tab' => 'seguridad',
                'activity_search' => 'firma',
            ]))
            ->assertOk()
            ->assertSee('Cambió su firma digital')
            ->assertDontSee('Evento de seguridad 9');
    }

    public function test_password_change_is_recorded_without_sensitive_values(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patchJson(route('configuracion.password.update'), [
                'current_password' => 'SecurePassword1',
                'password' => 'ChangedPassword2',
                'password_confirmation' => 'ChangedPassword2',
            ])
            ->assertOk();

        $log = ActivityLog::query()
            ->where('action', 'password_changed')
            ->firstOrFail();

        $this->assertSame('Cambió su contraseña', $log->description);
        $this->assertNull($log->metadata);
        $this->assertStringNotContainsString('ChangedPassword2', $log->description);
    }

    private function user(): User
    {
        return User::create([
            'name' => 'Doctor Auditoría',
            'email' => 'auditoria'.uniqid().'@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);
    }
}
