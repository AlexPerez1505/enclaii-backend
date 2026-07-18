<?php

namespace Tests\Feature;

use App\Models\ConfigurationBackup;
use App\Models\User;
use App\Services\ConfigurationBackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ConfigurationBackupTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_an_encrypted_complete_configuration_backup(): void
    {
        $user = $this->user([
            'phone' => '722 123 4567',
            'specialty' => 'Endoscopia',
            'settings' => [
                'timezone' => 'America/Mexico_City',
                'compact' => true,
            ],
        ]);

        $response = $this->actingAs($user)->postJson(route('configuracion.backups.store'), [
            'name' => 'Configuración principal',
            'mode' => 'complete',
            'scope' => [],
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('backup.name', 'Configuración principal');

        $backup = ConfigurationBackup::query()->firstOrFail();

        $this->assertSame(['general', 'profile'], $backup->scope);
        $this->assertSame('America/Mexico_City', $backup->payload['settings']['timezone']);
        $this->assertSame('722 123 4567', $backup->payload['profile']['phone']);

        $encryptedPayload = DB::table('configuration_backups')->value('payload');
        $this->assertStringNotContainsString('America/Mexico_City', $encryptedPayload);
        $this->assertStringNotContainsString('722 123 4567', $encryptedPayload);
    }

    public function test_restoring_a_backup_creates_an_automatic_rollback_copy(): void
    {
        $user = $this->user([
            'phone' => '111 111 1111',
            'specialty' => 'Endoscopia',
            'settings' => ['compact' => false, 'animations' => true],
        ]);

        $backup = app(ConfigurationBackupService::class)->create(
            $user,
            'Estado original',
            ConfigurationBackupService::SCOPES,
        );

        $user->forceFill([
            'phone' => '999 999 9999',
            'specialty' => 'Gastroenterología',
            'settings' => ['compact' => true, 'animations' => false],
        ])->save();

        $this->actingAs($user)
            ->postJson(route('configuracion.backups.restore', $backup->id))
            ->assertOk()
            ->assertJsonPath('ok', true);

        $user->refresh();

        $this->assertSame('111 111 1111', $user->phone);
        $this->assertSame('Endoscopia', $user->specialty);
        $this->assertFalse($user->settings['compact']);
        $this->assertTrue($user->settings['animations']);

        $rollback = $user->configurationBackups()
            ->where('type', 'automatic')
            ->firstOrFail();

        $this->assertSame('999 999 9999', $rollback->payload['profile']['phone']);
        $this->assertSame('Gastroenterología', $rollback->payload['profile']['specialty']);
        $this->assertTrue($rollback->payload['settings']['compact']);
        $this->assertNotNull($backup->fresh()->restored_at);
    }

    public function test_user_cannot_access_another_users_backup(): void
    {
        $owner = $this->user(['email' => 'owner@example.com']);
        $otherUser = $this->user(['email' => 'other@example.com']);
        $backup = app(ConfigurationBackupService::class)->create(
            $owner,
            'Copia privada',
            ConfigurationBackupService::SCOPES,
        );

        $this->actingAs($otherUser)
            ->postJson(route('configuracion.backups.restore', $backup->id))
            ->assertNotFound();

        $this->actingAs($otherUser)
            ->get(route('configuracion.backups.download', $backup->id))
            ->assertNotFound();

        $this->actingAs($otherUser)
            ->deleteJson(route('configuracion.backups.destroy', $backup->id))
            ->assertNotFound();

        $this->assertDatabaseHas('configuration_backups', ['id' => $backup->id]);
    }

    public function test_custom_backup_requires_at_least_one_valid_scope(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->postJson(route('configuracion.backups.store'), [
                'name' => 'Copia vacía',
                'mode' => 'custom',
                'scope' => [],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('scope');

        $this->assertDatabaseCount('configuration_backups', 0);
    }

    public function test_integrations_page_renders_the_backup_center_and_history(): void
    {
        $user = $this->user(['subscription_status' => 'active']);
        app(ConfigurationBackupService::class)->create(
            $user,
            'Copia visible',
            ConfigurationBackupService::SCOPES,
        );

        $this->actingAs($user)
            ->get(route('configuracion', ['tab' => 'integraciones']))
            ->assertOk()
            ->assertSee('Copias de configuración')
            ->assertSee('Copia visible')
            ->assertSee('Crear copia de configuración')
            ->assertDontSee('Catálogos del sistema')
            ->assertDontSee('Agregar personal');
    }

    private function user(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Doctor Prueba',
            'email' => 'doctor'.uniqid().'@example.com',
            'password' => 'password',
            'subscription_status' => 'active',
        ], $attributes));
    }
}
