<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NotifyDesktopAppUpdate extends Command
{
    protected $signature = 'desktop-app:notificar-actualizacion
        {--release-version= : Version de ENCLAII Desktop a notificar}
        {--path= : Ruta del instalador dentro del disk downloads}
        {--name= : Nombre de descarga del instalador}
        {--size= : Tamano visible del instalador}
        {--force : Vuelve a notificar aunque la version ya haya sido registrada}
        {--skip-exists-check : No valida si el instalador existe en el disk downloads}';

    protected $description = 'Notifica a los usuarios que hay una nueva version de la aplicacion de escritorio';

    public function handle(): int
    {
        $version = trim((string) ($this->option('release-version') ?: config('desktop_app.version')));
        $installerPath = trim((string) ($this->option('path') ?: config('desktop_app.installer_path')));
        $downloadName = trim((string) ($this->option('name') ?: config('desktop_app.download_name')));
        $size = trim((string) ($this->option('size') ?: config('desktop_app.size')));
        $force = (bool) $this->option('force');

        if ($version === '' || $installerPath === '') {
            $this->error('Falta configurar la version o la ruta del instalador de ENCLAII Desktop.');
            return self::FAILURE;
        }

        if (! $this->option('skip-exists-check') && ! $this->installerExists($installerPath)) {
            $this->error("No se encontro el instalador en downloads: {$installerPath}");
            return self::FAILURE;
        }

        $targetUsers = User::query()
            ->with('roles')
            ->orderBy('id')
            ->get()
            ->filter(fn (User $user) => $this->shouldNotify($user));

        $now = now();
        $downloadUrl = route('desktop-app.download', [], false);
        $payload = [
            'titulo' => 'Nueva aplicacion de escritorio disponible',
            'message' => "Ya esta disponible ENCLAII Desktop v{$version}. Descarga e instala la actualizacion.",
            'version' => $version,
            'download_url' => $downloadUrl,
            'download_name' => $downloadName,
            'installer_path' => $installerPath,
            'size' => $size,
            'platform' => 'Windows',
        ];

        $notifications = $targetUsers
            ->map(fn (User $user) => [
                'user_id' => $user->id,
                'tipo' => 'desktop_app_update',
                'data' => json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'read' => false,
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        $notifiedCount = DB::transaction(function () use ($force, $installerPath, $notifications, $now, $version) {
            if (! $force) {
                $reserved = DB::table('desktop_app_release_notifications')->insertOrIgnore([
                    'version' => $version,
                    'installer_path' => $installerPath,
                    'target_count' => 0,
                    'notified_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                if ($reserved === 0) {
                    return null;
                }
            }

            if ($notifications !== []) {
                Notification::insert($notifications);
            }

            DB::table('desktop_app_release_notifications')->updateOrInsert(
                ['version' => $version],
                [
                    'installer_path' => $installerPath,
                    'target_count' => count($notifications),
                    'notified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            return count($notifications);
        });

        if ($notifiedCount === null) {
            $this->info("La version {$version} ya fue notificada.");
            return self::SUCCESS;
        }

        $this->info("Notificacion de ENCLAII Desktop v{$version} creada para {$notifiedCount} usuarios.");

        return self::SUCCESS;
    }

    private function installerExists(string $installerPath): bool
    {
        try {
            return Storage::disk('downloads')->exists($installerPath);
        } catch (\Throwable) {
            return false;
        }
    }

    private function shouldNotify(User $user): bool
    {
        if ($user->hasRole('Customer Success') || ! $user->subscribed()) {
            return false;
        }

        return $user->resolvedSettings()['notif_updates_screen'] ?? true;
    }
}
