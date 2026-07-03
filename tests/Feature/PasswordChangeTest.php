<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_password_and_keep_current_session(): void
    {
        Event::fake([OtherDeviceLogout::class]);
        $user = $this->user();

        $this->actingAs($user)
            ->patchJson(route('configuracion.password.update'), [
                'current_password' => 'OldPassword1',
                'password' => 'NewSecurePassword2',
                'password_confirmation' => 'NewSecurePassword2',
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $user->refresh();

        $this->assertTrue(Hash::check('NewSecurePassword2', $user->password));
        $this->assertFalse(Hash::check('OldPassword1', $user->password));
        $this->assertNotNull($user->password_changed_at);
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(OtherDeviceLogout::class);
    }

    public function test_current_password_must_be_correct(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patchJson(route('configuracion.password.update'), [
                'current_password' => 'IncorrectPassword1',
                'password' => 'NewSecurePassword2',
                'password_confirmation' => 'NewSecurePassword2',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('current_password');

        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
        $this->assertNull($user->fresh()->password_changed_at);
    }

    public function test_new_password_must_be_strong_confirmed_and_different(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patchJson(route('configuracion.password.update'), [
                'current_password' => 'OldPassword1',
                'password' => 'weak',
                'password_confirmation' => 'different',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');

        $this->actingAs($user)
            ->patchJson(route('configuracion.password.update'), [
                'current_password' => 'OldPassword1',
                'password' => 'OldPassword1',
                'password_confirmation' => 'OldPassword1',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    }

    public function test_security_page_renders_real_password_status_and_modal(): void
    {
        $user = $this->user([
            'subscription_status' => 'active',
            'password_changed_at' => now()->setDate(2026, 7, 2)->setTime(12, 30),
        ]);

        $this->actingAs($user)
            ->get(route('configuracion', ['tab' => 'seguridad']))
            ->assertOk()
            ->assertSee('Cambiar contraseña')
            ->assertSee('02/07/2026 12:30')
            ->assertSee(route('configuracion.password.update'), false);
    }

    private function user(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Doctor Seguridad',
            'email' => 'seguridad'.uniqid().'@example.com',
            'password' => 'OldPassword1',
            'subscription_status' => 'active',
        ], $attributes));
    }
}
