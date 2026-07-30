<?php

namespace App\Support;

final class DesktopAppRelease
{
    private const CURRENT = [
        'version' => '0.1.9',
        'architecture' => '64 bits',
        'size' => '18.7 MB',
        'installer_path' => 'windows/releases/0.1.9/ENCLAII_0.1.9_x64_es-ES.msi',
        'download_name' => 'ENCLAII_0.1.9_x64_es-ES.msi',
    ];

    public static function current(): array
    {
        return self::CURRENT;
    }
}
