<?php

namespace Tests\Feature;

use App\Models\Clinica;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DesktopAppReleaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_desktop_app_defaults_point_to_current_release(): void
    {
        $this->assertSame('0.1.9', config('desktop_app.version'));
        $this->assertSame('18.7 MB', config('desktop_app.size'));
        $this->assertSame('windows/releases/0.1.9/ENCLAII_0.1.9_x64_es-ES.msi', config('desktop_app.installer_path'));
        $this->assertSame('ENCLAII_0.1.9_x64_es-ES.msi', config('desktop_app.download_name'));
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

        $this->assertSame('0.1.9', $notification->data['version']);
        $this->assertSame('Windows', $notification->data['platform']);
        $this->assertSame('18.7 MB', $notification->data['size']);
        $this->assertSame('windows/releases/0.1.9/ENCLAII_0.1.9_x64_es-ES.msi', $notification->data['installer_path']);

        $secondExitCode = Artisan::call('desktop-app:notificar-actualizacion', [
            '--skip-exists-check' => true,
        ]);

        $this->assertSame(0, $secondExitCode);
        $this->assertSame(1, Notification::where('tipo', 'desktop_app_update')->count());
    }
}
