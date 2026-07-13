<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('user_date_format')) {
    function user_date_format(): string
    {
        $user = auth()->user();
        $format = $user?->settings['date_format'] ?? 'DD/MM/YYYY';

        return match ($format) {
            'MM/DD/YYYY' => 'm/d/Y',
            'YYYY-MM-DD' => 'Y-m-d',
            default => 'd/m/Y',
        };
    }
}

if (! function_exists('user_time_format')) {
    function user_time_format(): string
    {
        $user = auth()->user();
        $format = $user?->settings['time_format'] ?? '12 horas (AM/PM)';

        return $format === '24 horas' ? 'H:i' : 'g:i A';
    }
}

if (! function_exists('user_date_time_format')) {
    function user_date_time_format(): string
    {
        return user_date_format().' '.user_time_format();
    }
}

if (! function_exists('format_user_date')) {
    function format_user_date($date, ?string $format = null): string
    {
        if (! $date) {
            return '';
        }

        $format ??= user_date_format();

        try {
            $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);

            return $carbon->format($format);
        } catch (Throwable) {
            return '';
        }
    }
}

if (! function_exists('format_user_date_time')) {
    function format_user_date_time($date): string
    {
        return format_user_date($date, user_date_time_format());
    }
}

if (! function_exists('format_user_time')) {
    function format_user_time($date): string
    {
        return format_user_date($date, user_time_format());
    }
}

if (! function_exists('format_user_time_with_seconds')) {
    function format_user_time_with_seconds($date): string
    {
        $format = user_time_format() === 'H:i' ? 'H:i:s' : 'g:i:s A';

        return format_user_date($date, $format);
    }
}

if (! function_exists('format_user_datetime')) {
    function format_user_datetime($date): string
    {
        return format_user_date_time($date);
    }
}

if (! function_exists('media_disk')) {
    function media_disk(): string
    {
        return (string) config('filesystems.media_disk', 'public');
    }
}

if (! function_exists('media_store')) {
    function media_store($file, string $directory): string
    {
        return $file->store($directory, media_disk());
    }
}

if (! function_exists('media_store_as')) {
    function media_store_as($file, string $directory, string $name): string
    {
        return $file->storeAs($directory, $name, media_disk());
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path, ?int $minutes = null): string
    {
        if (! $path) {
            return '';
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $disk = media_disk();
        $storage = Storage::disk($disk);
        $signedUrls = (bool) config('filesystems.media_signed_urls', $disk === 's3');

        if ($signedUrls && method_exists($storage, 'temporaryUrl')) {
            try {
                $ttl = $minutes ?? (int) config('filesystems.media_url_ttl', 60);

                return $storage->temporaryUrl($path, now()->addMinutes(max(1, $ttl)));
            } catch (Throwable) {
                // Fall back to regular URLs for disks that do not support signing.
            }
        }

        return $storage->url($path);
    }
}

if (! function_exists('media_exists')) {
    function media_exists(?string $path): bool
    {
        return filled($path) && Storage::disk(media_disk())->exists($path);
    }
}

if (! function_exists('media_delete')) {
    function media_delete(?string $path): bool
    {
        if (! filled($path)) {
            return false;
        }

        return Storage::disk(media_disk())->delete($path);
    }
}
