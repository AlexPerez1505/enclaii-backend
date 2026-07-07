<?php

namespace App\Services;

use App\Models\ConfigurationBackup;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class ConfigurationBackupService
{
    public const VERSION = 1;

    public const SCOPES = [
        'general',
        'profile',
    ];

    private const PROFILE_FIELDS = [
        'phone',
        'specialty',
        'professional_license',
        'medical_area',
        'position',
    ];

    public function create(
        User $user,
        string $name,
        array $scopes = self::SCOPES,
        string $type = 'manual',
    ): ConfigurationBackup {
        $scopes = $this->normalizeScopes($scopes);
        $payload = [];

        if (in_array('general', $scopes, true)) {
            $payload['settings'] = $user->settings ?? [];
        }

        if (in_array('profile', $scopes, true)) {
            $payload['profile'] = Arr::only($user->getAttributes(), self::PROFILE_FIELDS);
        }

        $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($encoded === false) {
            throw new RuntimeException('No se pudo preparar la copia de configuración.');
        }

        return $user->configurationBackups()->create([
            'uuid' => (string) Str::uuid(),
            'name' => Str::limit(trim($name), 100, ''),
            'type' => $type,
            'version' => self::VERSION,
            'scope' => $scopes,
            'payload' => $payload,
            'status' => 'completed',
            'size' => strlen($encoded),
        ]);
    }

    public function restore(User $user, ConfigurationBackup $backup): ConfigurationBackup
    {
        if ($backup->version !== self::VERSION) {
            throw new RuntimeException('Esta copia pertenece a una versión no compatible.');
        }

        $payload = $backup->payload;

        if (! is_array($payload)) {
            throw new RuntimeException('La copia de configuración está dañada.');
        }

        return DB::transaction(function () use ($user, $backup, $payload) {
            $rollback = $this->create(
                $user,
                'Antes de restaurar: '.$backup->name,
                self::SCOPES,
                'automatic',
            );

            if (array_key_exists('settings', $payload)) {
                $user->settings = is_array($payload['settings']) ? $payload['settings'] : [];
            }

            if (isset($payload['profile']) && is_array($payload['profile'])) {
                $user->forceFill(Arr::only($payload['profile'], self::PROFILE_FIELDS));
            }

            $user->save();

            $backup->forceFill(['restored_at' => now()])->save();

            return $rollback;
        });
    }

    private function normalizeScopes(array $scopes): array
    {
        $scopes = array_values(array_intersect(self::SCOPES, array_unique($scopes)));

        if ($scopes === []) {
            throw new RuntimeException('Selecciona al menos una sección para crear la copia.');
        }

        return $scopes;
    }
}
