<?php

namespace App\Support;

final class DesktopAppRelease
{
    private const CURRENT = [
        'version' => '0.2.3',
        'architecture' => '64 bits',
        'size' => '16.9 MB',
        'installer_path' => 'windows/releases/0.2.3/ENCLAII_0.2.3_x64-setup.exe',
        'download_name' => 'ENCLAII_0.2.3_x64-setup.exe',
    ];

    public static function current(): array
    {
        return self::CURRENT;
    }
}
