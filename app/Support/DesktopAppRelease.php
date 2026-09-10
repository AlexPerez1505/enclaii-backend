<?php

namespace App\Support;

final class DesktopAppRelease
{
    public const PLATFORM_WINDOWS = 'windows';

    public const PLATFORM_MAC = 'mac';

    private const CURRENT_PLATFORM = self::PLATFORM_WINDOWS;

    private const RELEASES = [
        self::PLATFORM_WINDOWS => [
            'platform_key' => self::PLATFORM_WINDOWS,
            'platform' => 'Windows',
            'version' => '0.2.3',
            'architecture' => '64 bits',
            'size' => '16.9 MB',
            'installer_path' => 'windows/releases/0.2.3/ENCLAII_0.2.3_x64-setup.exe',
            'download_name' => 'ENCLAII_0.2.3_x64-setup.exe',
        ],
        self::PLATFORM_MAC => [
            'platform_key' => self::PLATFORM_MAC,
            'platform' => 'macOS',
            'version' => '0.2.3',
            'architecture' => '64 bits',
            'size' => '19.3 MB',
            'installer_path' => 'mac/releases/0.2.3r/ENCLAII_0.2.3_x64.dmg',
            'download_name' => 'ENCLAII_0.2.3_x64.dmg',
            'mime_type' => 'application/x-apple-diskimage',
        ],
    ];

    public static function current(): array
    {
        return self::forPlatform(self::CURRENT_PLATFORM);
    }

    public static function forPlatform(string $platform): array
    {
        return self::RELEASES[self::normalizePlatform($platform)] ?? [];
    }

    private static function normalizePlatform(string $platform): string
    {
        return match (strtolower(trim($platform))) {
            'darwin', 'macos', 'osx' => self::PLATFORM_MAC,
            default => strtolower(trim($platform)),
        };
    }
}
