<?php

use Illuminate\Support\Carbon;

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
