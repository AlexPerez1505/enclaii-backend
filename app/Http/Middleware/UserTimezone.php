<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $timezone = $user->settings['timezone'] ?? config('app.timezone');
            if (in_array($timezone, timezone_identifiers_list(), true)) {
                date_default_timezone_set($timezone);
            }
        }

        return $next($request);
    }
}
