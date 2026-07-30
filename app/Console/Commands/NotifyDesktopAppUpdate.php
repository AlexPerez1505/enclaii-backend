<?php

namespace App\Console\Commands;

use App\Services\DesktopAppReleaseNotificationService;
use App\Support\DesktopAppRelease;
use Illuminate\Console\Command;

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

    public function handle(DesktopAppReleaseNotificationService $notifier): int
    {
        $release = DesktopAppRelease::current();

        $release['version'] = trim((string) ($this->option('release-version') ?: $release['version']));
        $release['installer_path'] = trim((string) ($this->option('path') ?: $release['installer_path']));
        $release['download_name'] = trim((string) ($this->option('name') ?: $release['download_name']));
        $release['size'] = trim((string) ($this->option('size') ?: $release['size']));

        $result = $notifier->notifyRelease(
            $release,
            (bool) $this->option('force'),
            (bool) $this->option('skip-exists-check'),
        );

        if (! $result['ok']) {
            $this->error($result['message']);

            return self::FAILURE;
        }

        $this->info($result['message']);

        return self::SUCCESS;
    }
}
