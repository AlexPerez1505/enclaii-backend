<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerSuccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->hasRole('Customer Success')) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Acceso restringido.'], 403)
                : redirect()->route('login')->with('error', 'No tienes acceso a esta sección.');
        }

        return $next($request);
    }
}
