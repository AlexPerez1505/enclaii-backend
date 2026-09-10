<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifica que /login y /registro tengan protección contra fuerza bruta
 * (rate limiting), para que este control de seguridad no se elimine sin
 * darse cuenta en un cambio futuro.
 */
class AuthThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited_after_five_attempts_from_the_same_ip(): void
    {
        $user = $this->user();

        $attempt = fn () => $this
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
            ->post(route('login.post'), [
                'email' => $user->email,
                'password' => 'contraseña-incorrecta',
            ]);

        for ($i = 0; $i < 5; $i++) {
            $attempt()->assertStatus(302); // credenciales inválidas, pero no bloqueado todavía
        }

        // El sexto intento en la misma ventana de tiempo debe ser bloqueado.
        $attempt()->assertStatus(429);
    }

    public function test_register_is_rate_limited_after_five_attempts_from_the_same_ip(): void
    {
        // Password sin confirmar a propósito: la validación falla siempre y el
        // usuario nunca queda autenticado, así el middleware "guest" no
        // interfiere y podemos medir el throttle de forma aislada.
        $attempt = fn () => $this
            ->withServerVariables(['REMOTE_ADDR' => '203.0.113.20'])
            ->post(route('register.post'), [
                'name' => 'Usuario Prueba',
                'email' => 'throttle-register@example.com',
                'password' => 'SecurePassword1',
                'password_confirmation' => 'ContraseñaDistinta',
                'vertical' => 'medica',
            ]);

        for ($i = 0; $i < 5; $i++) {
            $attempt()->assertStatus(302)->assertSessionHasErrors('password');
        }

        $attempt()->assertStatus(429);
    }

    private function user(): User
    {
        return User::create([
            'name' => 'Doctor Throttle',
            'email' => 'throttle-login'.uniqid().'@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);
    }
}
