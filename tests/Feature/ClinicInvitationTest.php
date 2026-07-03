<?php

namespace Tests\Feature;

use App\Models\Clinica;
use App\Models\ClinicaInvitation;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_invite_a_member_by_email(): void
    {
        [$clinic, $owner] = $this->clinicOwner();

        $response = $this->actingAs($owner)->postJson(
            route('configuracion.clinic-invitations.store'),
            ['email' => 'nuevo@example.com', 'rol' => 'medico'],
        );

        $response->assertCreated()
            ->assertJsonPath(
                'message',
                'Correo autorizado. Cuando la persona cree su cuenta quedará dentro de esta clínica.',
            );

        $this->assertDatabaseHas('clinica_invitations', [
            'clinica_id' => $clinic->id,
            'email' => 'nuevo@example.com',
            'rol' => 'medico',
        ]);
    }

    public function test_regular_member_cannot_invite_or_manage_members(): void
    {
        [$clinic, $owner] = $this->clinicOwner();
        $member = User::create([
            'clinica_id' => $clinic->id,
            'clinica_rol' => 'medico',
            'name' => 'Médico',
            'email' => 'member@example.com',
            'password' => 'SecurePassword1',
        ]);

        $this->actingAs($member)
            ->postJson(route('configuracion.clinic-invitations.store'), [
                'email' => 'otro@example.com',
                'rol' => 'medico',
            ])
            ->assertForbidden();

        $this->actingAs($member)
            ->deleteJson(route('configuracion.clinic-members.destroy', $owner))
            ->assertForbidden();
    }

    public function test_authorized_email_registration_inherits_clinic_plan_and_data(): void
    {
        [$clinic, $owner] = $this->clinicOwner();
        $patient = Paciente::create([
            'clinica_id' => $clinic->id,
            'folio' => 'P-SHARED',
            'nombre_completo' => 'Paciente Compartido',
        ]);
        ClinicaInvitation::create([
            'clinica_id' => $clinic->id,
            'invited_by' => $owner->id,
            'email' => 'invited@example.com',
            'rol' => 'medico',
            'token_hash' => hash('sha256', 'internal-authorization'),
            'expires_at' => now()->addYears(10),
        ]);

        $this->post(route('register.post'), [
            'name' => 'Invitado',
            'email' => 'invited@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
        ])
            ->assertRedirect(route('dashboard'));

        $invited = User::query()->where('email', 'invited@example.com')->firstOrFail();
        $this->assertSame($clinic->id, $invited->clinica_id);
        $this->assertSame('medico', $invited->clinica_rol);
        $this->assertTrue($invited->subscribed());
        $this->assertSame([$patient->id], Paciente::query()->pluck('id')->all());
    }

    public function test_plan_member_limit_counts_pending_invitations(): void
    {
        [$clinic, $owner] = $this->clinicOwner();

        for ($i = 1; $i <= 4; $i++) {
            ClinicaInvitation::create([
                'clinica_id' => $clinic->id,
                'invited_by' => $owner->id,
                'email' => "pending{$i}@example.com",
                'rol' => 'medico',
                'token_hash' => hash('sha256', 'token-'.$i),
                'expires_at' => now()->addDays(7),
            ]);
        }

        $this->actingAs($owner)
            ->postJson(route('configuracion.clinic-invitations.store'), [
                'email' => 'overflow@example.com',
                'rol' => 'medico',
            ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Tu plan ya alcanzó el límite de integrantes e invitaciones pendientes.');
    }

    public function test_unlisted_email_must_select_a_plan_before_accessing_the_system(): void
    {
        $this->post(route('register.post'), [
            'name' => 'Usuario sin autorización',
            'email' => 'unlisted@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
        ])
            ->assertRedirect(route('plan.only'));

        $sharedClinic = Clinica::query()->where('is_shared', true)->firstOrFail();
        $this->assertDatabaseHas('users', [
            'email' => 'unlisted@example.com',
            'clinica_id' => $sharedClinic->id,
            'clinica_rol' => 'usuario',
        ]);

        $this->get(route('pacientes.index'))->assertRedirect(route('plan.only'));
        $this->get(route('dashboard'))->assertRedirect(route('plan.only'));
        $this->get(route('plan.only'))->assertOk();
    }

    public function test_unsubscribed_user_is_sent_to_plan_selection_after_login(): void
    {
        User::create([
            'name' => 'Usuario sin plan',
            'email' => 'without-plan@example.com',
            'password' => 'SecurePassword1',
        ]);

        $this->post(route('login.post'), [
            'email' => 'without-plan@example.com',
            'password' => 'SecurePassword1',
        ])->assertRedirect(route('plan.only'));
    }

    public function test_each_activated_plan_receives_a_different_private_clinic(): void
    {
        $first = User::create([
            'name' => 'Plan Uno',
            'email' => 'plan1@example.com',
            'password' => 'SecurePassword1',
        ]);
        $second = User::create([
            'name' => 'Plan Dos',
            'email' => 'plan2@example.com',
            'password' => 'SecurePassword1',
        ]);

        $this->assertSame($first->clinica_id, $second->clinica_id);
        $this->assertTrue($first->clinica->is_shared);

        $first->forceFill([
            'stripe_plan' => 'clinica',
            'subscription_status' => 'active',
        ])->save();
        $second->forceFill([
            'stripe_plan' => 'clinica',
            'subscription_status' => 'active',
        ])->save();

        $first->refresh();
        $second->refresh();

        $this->assertNotSame($first->clinica_id, $second->clinica_id);
        $this->assertFalse($first->clinica->is_shared);
        $this->assertFalse($second->clinica->is_shared);

        $this->actingAs($first);
        $firstPatient = Paciente::create([
            'folio' => 'P-PLAN-1',
            'nombre_completo' => 'Paciente Plan Uno',
        ]);

        $this->actingAs($second);
        $secondPatient = Paciente::create([
            'folio' => 'P-PLAN-2',
            'nombre_completo' => 'Paciente Plan Dos',
        ]);

        $this->assertSame([$secondPatient->id], Paciente::query()->pluck('id')->all());

        $this->actingAs($first);
        $this->assertSame([$firstPatient->id], Paciente::query()->pluck('id')->all());
    }

    private function clinicOwner(): array
    {
        $clinic = Clinica::create(['nombre' => 'Clínica Compartida']);
        $owner = User::create([
            'clinica_id' => $clinic->id,
            'clinica_rol' => 'propietario',
            'name' => 'Propietario',
            'email' => 'owner'.uniqid().'@example.com',
            'password' => 'SecurePassword1',
            'stripe_plan' => 'clinica',
            'subscription_status' => 'active',
        ]);

        return [$clinic, $owner];
    }
}
