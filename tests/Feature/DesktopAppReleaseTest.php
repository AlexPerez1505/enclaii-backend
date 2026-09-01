<?php

namespace Tests\Feature;

use App\Models\Clinica;
use App\Models\Notification;
use App\Models\User;
use App\Support\DesktopAppRelease;
use DateTimeInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DesktopAppReleaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_desktop_app_defaults_point_to_current_release(): void
    {
        $this->assertSame('0.2.3', config('desktop_app.version'));
        $this->assertSame('16.9 MB', config('desktop_app.size'));
        $this->assertSame('windows/releases/0.2.3/ENCLAII_0.2.3_x64-setup.exe', config('desktop_app.installer_path'));
        $this->assertSame('ENCLAII_0.2.3_x64-setup.exe', config('desktop_app.download_name'));

        $macRelease = DesktopAppRelease::forPlatform('mac');

        $this->assertSame('macOS', $macRelease['platform']);
        $this->assertSame('0.1.0', $macRelease['version']);
        $this->assertSame('16.8 MB', $macRelease['size']);
        $this->assertSame('mac/releases/0.1.0/endoscopy-capture.app.zip', $macRelease['installer_path']);
        $this->assertSame('endoscopy-capture.app.zip', $macRelease['download_name']);
    }

    public function test_subscribed_user_can_download_macos_release(): void
    {
        $release = DesktopAppRelease::forPlatform('mac');

        Storage::fake('downloads');
        Storage::disk('downloads')->put($release['installer_path'], 'zip');

        $temporaryUrlCall = [];

        Storage::disk('downloads')->buildTemporaryUrlsUsing(function ($path, $expiration, array $options) use (&$temporaryUrlCall) {
            $temporaryUrlCall = [
                'path' => $path,
                'expiration' => $expiration,
                'options' => $options,
            ];

            return 'https://downloads.example.test/endoscopy-capture.app.zip';
        });

        $clinica = Clinica::create([
            'nombre' => 'Clinica Mac Desktop',
            'is_shared' => false,
        ]);

        $user = User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor Mac Desktop',
            'email' => 'doctor-mac-desktop@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        $response = $this->actingAs($user)->get(route('desktop-app.download.mac'));

        $response->assertRedirect('https://downloads.example.test/endoscopy-capture.app.zip');

        $this->assertSame('mac/releases/0.1.0/endoscopy-capture.app.zip', $temporaryUrlCall['path']);
        $this->assertInstanceOf(DateTimeInterface::class, $temporaryUrlCall['expiration']);
        $this->assertStringContainsString('filename="endoscopy-capture.app.zip"', $temporaryUrlCall['options']['ResponseContentDisposition']);
    }

    public function test_desktop_app_settings_show_macos_download(): void
    {
        $user = User::create([
            'name' => 'Doctor Config Mac',
            'email' => 'doctor-config-mac@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('configuracion', ['tab' => 'aplicacion-escritorio']))
            ->assertOk()
            ->assertSee('Descargar para macOS')
            ->assertSee('v0.1.0')
            ->assertSee(route('desktop-app.download.mac'), false)
            ->assertDontSee('Descarga no disponible');
    }

    public function test_desktop_app_update_command_notifies_current_release_once(): void
    {
        $clinica = Clinica::create([
            'nombre' => 'Clinica Desktop',
            'is_shared' => false,
        ]);

        $user = User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor Desktop',
            'email' => 'doctor-desktop@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        $exitCode = Artisan::call('desktop-app:notificar-actualizacion', [
            '--skip-exists-check' => true,
        ]);

        $this->assertSame(0, $exitCode);

        $notification = Notification::query()
            ->where('user_id', $user->id)
            ->where('tipo', 'desktop_app_update')
            ->firstOrFail();

        $this->assertSame('0.2.3', $notification->data['version']);
        $this->assertSame('Windows', $notification->data['platform']);
        $this->assertSame('16.9 MB', $notification->data['size']);
        $this->assertSame('windows/releases/0.2.3/ENCLAII_0.2.3_x64-setup.exe', $notification->data['installer_path']);

        $secondExitCode = Artisan::call('desktop-app:notificar-actualizacion', [
            '--skip-exists-check' => true,
        ]);

        $this->assertSame(0, $secondExitCode);
        $this->assertSame(1, Notification::where('tipo', 'desktop_app_update')->count());
    }

    public function test_notification_poll_creates_desktop_update_when_scheduler_has_not_run(): void
    {
        Storage::fake('downloads');
        Storage::disk('downloads')->put(config('desktop_app.installer_path'), 'installer');

        $clinica = Clinica::create([
            'nombre' => 'Clinica Desktop',
            'is_shared' => false,
        ]);

        $user = User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor Desktop',
            'email' => 'doctor-desktop-poll@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        $response = $this->actingAs($user)->getJson(route('notifications.index'));

        $response->assertOk()
            ->assertJsonFragment([
                'tipo' => 'desktop_app_update',
                'version' => '0.2.3',
                'size' => '16.9 MB',
                'installer_path' => 'windows/releases/0.2.3/ENCLAII_0.2.3_x64-setup.exe',
            ]);

        $this->assertSame(1, Notification::where('tipo', 'desktop_app_update')->count());
    }

    public function test_notification_poll_repairs_missing_user_notification_when_release_marker_exists(): void
    {
        Storage::fake('downloads');
        Storage::disk('downloads')->put(config('desktop_app.installer_path'), 'installer');

        DB::table('desktop_app_release_notifications')->insert([
            'version' => '0.2.3',
            'installer_path' => 'windows/releases/0.2.3/ENCLAII_0.2.3_x64-setup.exe',
            'target_count' => 0,
            'notified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $clinica = Clinica::create([
            'nombre' => 'Clinica Desktop',
            'is_shared' => false,
        ]);

        $user = User::create([
            'clinica_id' => $clinica->id,
            'clinica_rol' => 'propietario',
            'name' => 'Doctor Desktop',
            'email' => 'doctor-desktop-repair@example.com',
            'password' => 'SecurePassword1',
            'subscription_status' => 'active',
        ]);

        $response = $this->actingAs($user)->getJson(route('notifications.index'));

        $response->assertOk()
            ->assertJsonFragment([
                'tipo' => 'desktop_app_update',
                'version' => '0.2.3',
            ]);

        $this->assertSame(1, Notification::where('tipo', 'desktop_app_update')->count());
    }
}
