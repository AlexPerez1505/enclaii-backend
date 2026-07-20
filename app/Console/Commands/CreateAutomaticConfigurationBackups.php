<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ConfigurationBackupService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class CreateAutomaticConfigurationBackups extends Command
{
    protected $signature = 'backups:configuration-auto {--force : Crea copias sin validar la hora configurada}';

    protected $description = 'Crea copias automaticas de configuracion y perfil segun los ajustes del usuario';

    public function handle(ConfigurationBackupService $backups): int
    {
        $created = 0;
        $errors = 0;

        User::query()
            ->orderBy('id')
            ->chunkById(100, function ($users) use ($backups, &$created, &$errors): void {
                foreach ($users as $user) {
                    try {
                        if (! $user->subscribed()) {
                            continue;
                        }

                        $settings = $user->resolvedSettings();
                        $frequency = $settings['backup_frequency'] ?? 'manual';

                        if (! in_array($frequency, ['daily', 'weekly'], true)) {
                            continue;
                        }

                        $timezone = $settings['timezone'] ?? 'America/Mexico_City';
                        $now = Carbon::now($timezone);

                        if (! $this->option('force') && ! $this->isScheduledWindow($now, (string) ($settings['backup_time'] ?? '03:00'))) {
                            continue;
                        }

                        if ($this->automaticBackupExists($user, $frequency, $now)) {
                            continue;
                        }

                        $scope = array_values(array_intersect(
                            ConfigurationBackupService::SCOPES,
                            (array) ($settings['backup_scope'] ?? ConfigurationBackupService::SCOPES),
                        ));

                        if ($scope === []) {
                            $scope = ConfigurationBackupService::SCOPES;
                        }

                        $backups->create(
                            $user,
                            'Copia automatica '.$now->format('d/m/Y H:i'),
                            $scope,
                            'automatic',
                        );

                        $this->deleteExpiredAutomaticBackups($user, (int) ($settings['backup_retention_days'] ?? 30));
                        $created++;
                    } catch (Throwable $exception) {
                        $errors++;
                        $this->error("Usuario {$user->id}: {$exception->getMessage()}");
                    }
                }
            });

        $this->info("Copias automaticas creadas: {$created}");

        if ($errors > 0) {
            $this->warn("Usuarios con error: {$errors}");
        }

        return $errors > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function isScheduledWindow(Carbon $now, string $time): bool
    {
        if (! preg_match('/^\d{2}:\d{2}$/', $time)) {
            $time = '03:00';
        }

        [$hour, $minute] = array_map('intval', explode(':', $time));
        $scheduled = $now->copy()->setTime($hour, $minute);

        return $now->greaterThanOrEqualTo($scheduled)
            && $now->lessThan($scheduled->copy()->addMinutes(15));
    }

    private function automaticBackupExists(User $user, string $frequency, Carbon $now): bool
    {
        $periodStart = $frequency === 'weekly'
            ? $now->copy()->startOfWeek()
            : $now->copy()->startOfDay();

        $periodEnd = $frequency === 'weekly'
            ? $now->copy()->endOfWeek()
            : $now->copy()->endOfDay();

        $appTimezone = config('app.timezone', 'UTC');

        return $user->configurationBackups()
            ->where('type', 'automatic')
            ->whereBetween('created_at', [
                $periodStart->copy()->setTimezone($appTimezone),
                $periodEnd->copy()->setTimezone($appTimezone),
            ])
            ->exists();
    }

    private function deleteExpiredAutomaticBackups(User $user, int $retentionDays): void
    {
        $retentionDays = in_array($retentionDays, [7, 15, 30, 90], true) ? $retentionDays : 30;

        $user->configurationBackups()
            ->where('type', 'automatic')
            ->where('created_at', '<', now()->subDays($retentionDays))
            ->delete();
    }
}
