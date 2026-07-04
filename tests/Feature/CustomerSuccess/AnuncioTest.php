<?php

namespace Tests\Feature\CustomerSuccess;

use App\Models\Anuncio;
use App\Models\CustomerSuccessAuditLog;
use App\Models\User;
use Database\Seeders\CustomerSuccessRolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AnuncioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CustomerSuccessRolesSeeder::class);
    }

    private function customerSuccessUser(): User
    {
        $user = User::create([
            'name' => 'Customer Success',
            'email' => 'cs@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('Customer Success');

        return $user;
    }

    private function regularUser(): User
    {
        return User::create([
            'name' => 'Doctor Regular',
            'email' => 'doctor@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_customer_success_can_create_anuncio(): void
    {
        $user = $this->customerSuccessUser();

        Sanctum::actingAs($user);

        $response = $this->postJson(route('api.customer-success.anuncios.store'), [
            'titulo' => 'Aviso de mantenimiento',
            'contenido' => '<p>El sistema estará en mantenimiento el domingo.</p>',
            'tipo' => 'general',
            'activo' => true,
        ]);

        $response->assertCreated()
            ->assertJsonPath('titulo', 'Aviso de mantenimiento');

        $this->assertDatabaseHas('anuncios', [
            'titulo' => 'Aviso de mantenimiento',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('customer_success_audit_logs', [
            'user_id' => $user->id,
            'action' => 'created',
            'entity_type' => Anuncio::class,
        ]);
    }

    public function test_regular_user_cannot_create_anuncio(): void
    {
        $user = $this->regularUser();

        Sanctum::actingAs($user);

        $response = $this->postJson(route('api.customer-success.anuncios.store'), [
            'titulo' => 'Aviso no autorizado',
            'contenido' => '<p>Contenido</p>',
            'tipo' => 'general',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('anuncios', [
            'titulo' => 'Aviso no autorizado',
        ]);
    }

    public function test_guest_cannot_create_anuncio(): void
    {
        $response = $this->postJson(route('api.customer-success.anuncios.store'), [
            'titulo' => 'Aviso sin sesión',
            'contenido' => '<p>Contenido</p>',
            'tipo' => 'general',
        ]);

        $response->assertUnauthorized();
    }

    public function test_xss_content_is_sanitized(): void
    {
        $user = $this->customerSuccessUser();

        Sanctum::actingAs($user);

        $response = $this->postJson(route('api.customer-success.anuncios.store'), [
            'titulo' => 'XSS test',
            'contenido' => '<script>alert("xss")</script><p>Texto limpio</p>',
            'tipo' => 'general',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('anuncios', [
            'titulo' => 'XSS test',
            'contenido' => '<p>Texto limpio</p>',
        ]);
    }

    public function test_customer_success_can_view_dashboard(): void
    {
        $user = $this->customerSuccessUser();

        $this->actingAs($user)
            ->get(route('customer-success.dashboard'))
            ->assertOk()
            ->assertSee('Customer Success')
            ->assertSee('Bienvenido al panel de Customer Success');
    }

    public function test_customer_success_can_view_anuncios(): void
    {
        $user = $this->customerSuccessUser();

        $this->actingAs($user)
            ->get(route('customer-success.anuncios'))
            ->assertOk()
            ->assertSee('Customer Success');
    }

    public function test_regular_user_cannot_view_customer_success_dashboard(): void
    {
        $user = $this->regularUser();

        $this->actingAs($user)
            ->get(route('customer-success.anuncios'))
            ->assertForbidden();
    }

    public function test_customer_success_is_redirected_after_login(): void
    {
        $user = $this->customerSuccessUser();
        $user->password = bcrypt('password');
        $user->save();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect(route('customer-success.dashboard'));
    }

    public function test_regular_user_is_not_redirected_to_customer_success_dashboard(): void
    {
        $user = $this->regularUser();
        $user->password = bcrypt('password');
        $user->save();
        $user->settings = array_merge($user->settings ?? [], ['default_view' => 'Dashboard']);
        $user->save();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertRedirect('/configuracion');
    }

    public function test_customer_success_can_assign_role(): void
    {
        $admin = $this->customerSuccessUser();
        $target = $this->regularUser();

        Sanctum::actingAs($admin, ['*']);

        $this->postJson(route('api.customer-success.users.assign-role', $target), [
            'role' => 'Customer Success',
        ])
            ->assertOk()
            ->assertJson(['message' => 'Rol asignado correctamente.']);

        $this->assertTrue($target->fresh()->hasRole('Customer Success'));
    }

    public function test_customer_success_can_remove_role(): void
    {
        $admin = $this->customerSuccessUser();
        $target = $this->regularUser();
        $target->assignRole('Customer Success');

        Sanctum::actingAs($admin, ['*']);

        $this->postJson(route('api.customer-success.users.remove-role', $target), [
            'role' => 'Customer Success',
        ])
            ->assertOk()
            ->assertJson(['message' => 'Rol removido correctamente.']);

        $this->assertFalse($target->fresh()->hasRole('Customer Success'));
    }

    public function test_regular_user_cannot_assign_role(): void
    {
        $user = $this->regularUser();
        $target = $this->customerSuccessUser();

        Sanctum::actingAs($user, ['*']);

        $this->postJson(route('api.customer-success.users.assign-role', $target), [
            'role' => 'Customer Success',
        ])
            ->assertForbidden();
    }
}
