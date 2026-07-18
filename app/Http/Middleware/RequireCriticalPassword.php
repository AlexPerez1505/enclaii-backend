<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireCriticalPassword
{
    public function handle(Request $request, Closure $next, string $scope): Response
    {
        $user = $request->user();
        $mustConfirm = $scope === 'security_settings'
            || $user?->criticalPasswordRequired($scope);

        if (! $mustConfirm) {
            return $next($request);
        }

        $token = (string) ($request->header('X-Critical-Authorization')
            ?: $request->input('_critical_token', ''));
        $key = $token === '' ? '' : hash('sha256', $token);
        $authorization = $key === ''
            ? null
            : $request->session()->pull('critical_authorizations.'.$key);

        $valid = is_array($authorization)
            && (int) ($authorization['user_id'] ?? 0) === (int) $user?->id
            && ($authorization['scope'] ?? null) === $scope
            && (int) ($authorization['expires_at'] ?? 0) >= now()->timestamp;

        if (! $valid) {
            $message = 'Confirma tu contraseña para realizar esta acción.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => $message,
                    'code' => 'critical_password_required',
                ], 428);
            }

            return back()->withErrors(['critical_password' => $message]);
        }

        return $next($request);
    }
}
