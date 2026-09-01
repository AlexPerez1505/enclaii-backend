<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Support\DesktopAppRelease;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DesktopAppReleaseNotificationService
{
    public function notifyCurrentRelease(bool $force = false, bool $skipExistsCheck = false): array
    {
        return $this->notifyRelease(DesktopAppRelease::current(), $force, $skipExistsCheck);
    }

    public function notifyCurrentReleaseForUser(?User $user, bool $skipExistsCheck = false): array
    {
        if (! $user || ! $this->shouldNotify($user)) {
            return [
                'ok' => true,
                'already_notified' => false,
                'notified_count' => 0,
                'message' => 'Usuario fuera del publico objetivo.',
            ];
        }

        $release = DesktopAppRelease::current();
        $version = trim((string) ($release['version'] ?? ''));
        $installerPath = trim((string) ($release['installer_path'] ?? ''));

        if ($version === '' || $installerPath === '') {
            return [
                'ok' => false,
                'message' => 'Falta configurar la version o la ruta del instalador de ENCLAII Desktop.',
            ];
        }

        if ($this->userHasReleaseNotification($user, $version)) {
            return [
                'ok' => true,
                'already_notified' => true,
                'notified_count' => 0,
                'message' => "El usuario ya tiene la notificacion de ENCLAII Desktop v{$version}.",
            ];
        }

        if (! $skipExistsCheck && ! $this->installerExists($installerPath)) {
            return [
                'ok' => false,
                'message' => "No se encontro el instalador en downloads: {$installerPath}",
            ];
        }

        $now = now();

        DB::transaction(function () use ($installerPath, $now, $release, $user, $version) {
            if (! $this->userHasReleaseNotification($user, $version)) {
                Notification::create([
                    'user_id' => $user->id,
                    'tipo' => 'desktop_app_update',
                    'data' => $this->releasePayload($release),
                    'read' => false,
                    'read_at' => null,
                ]);
            }

            DB::table('desktop_app_release_notifications')->insertOrIgnore([
                'version' => $version,
                'installer_path' => $installerPath,
                'target_count' => 0,
                'notified_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('desktop_app_release_notifications')
                ->where('version', $version)
                ->update([
                    'installer_path' => $installerPath,
                    'target_count' => DB::raw('target_count + 1'),
                    'notified_at' => $now,
                    'updated_at' => $now,
                ]);
        });

        return [
            'ok' => true,
            'already_notified' => false,
            'notified_count' => 1,
            'message' => "Notificacion de ENCLAII Desktop v{$version} creada para el usuario {$user->id}.",
        ];
    }

    public function notifyRelease(array $release, bool $force = false, bool $skipExistsCheck = false): array
    {
        $version = trim((string) ($release['version'] ?? ''));
        $installerPath = trim((string) ($release['installer_path'] ?? ''));

        if ($version === '' || $installerPath === '') {
            return [
                'ok' => false,
                'message' => 'Falta configurar la version o la ruta del instalador de ENCLAII Desktop.',
            ];
        }

        if (! $force && DB::table('desktop_app_release_notifications')->where('version', $version)->exists()) {
            return [
                'ok' => true,
                'already_notified' => true,
                'notified_count' => null,
                'message' => "La version {$version} ya fue notificada.",
            ];
        }

        if (! $skipExistsCheck && ! $this->installerExists($installerPath)) {
            return [
                'ok' => false,
                'message' => "No se encontro el instalador en downloads: {$installerPath}",
            ];
        }

        $targetUsers = User::query()
            ->with('roles')
            ->orderBy('id')
            ->get()
            ->filter(fn (User $user) => $this->shouldNotify($user));

        $now = now();
        $payload = $this->releasePayload($release);

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
            return [
                'ok' => true,
                'already_notified' => true,
                'notified_count' => null,
                'message' => "La version {$version} ya fue notificada.",
            ];
        }

        return [
            'ok' => true,
            'already_notified' => false,
            'notified_count' => $notifiedCount,
            'message' => "Notificacion de ENCLAII Desktop v{$version} creada para {$notifiedCount} usuarios.",
        ];
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

    private function releasePayload(array $release): array
    {
        $version = trim((string) ($release['version'] ?? ''));

        return [
            'titulo' => 'Nueva aplicacion de escritorio disponible',
            'message' => "Ya esta disponible ENCLAII Desktop v{$version}. Descarga e instala la actualizacion.",
            'version' => $version,
            'download_url' => route('desktop-app.download', [], false),
            'download_name' => trim((string) ($release['download_name'] ?? '')),
            'installer_path' => trim((string) ($release['installer_path'] ?? '')),
            'size' => trim((string) ($release['size'] ?? '')),
            'platform' => trim((string) ($release['platform'] ?? 'Windows')) ?: 'Windows',
        ];
    }

    private function userHasReleaseNotification(User $user, string $version): bool
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->where('tipo', 'desktop_app_update')
            ->get()
            ->contains(fn (Notification $notification) => ($notification->data['version'] ?? null) === $version);
    }
}
