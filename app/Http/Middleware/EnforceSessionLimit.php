<?php

namespace App\Http\Middleware;

use App\Services\SessionLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceSessionLimit
{
    public function __construct(
        private readonly SessionLimitService $sessionLimits,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $response = $this->sessionLimits->checkInactivity($request, $user);

            if ($response !== null) {
                return $response;
            }

            $this->sessionLimits->syncCurrentDatabaseSession($request, $user);
            $closed = $this->sessionLimits->enforceDatabaseSessions(
                $user,
                $request->session()->getId(),
            );

            if ($closed > 0 && ! $request->expectsJson()) {
                $limit = $this->sessionLimits->limitFor($user);
                $request->session()->flash(
                    'session_limit_notice',
                    "Tu plan permite {$limit} sesiones activas por cuenta. Se cerraron {$closed} sesiones antiguas.",
                );
            }
        }

        return $next($request);
    }
}
