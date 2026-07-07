<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectCustomerSuccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('Customer Success')) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Acceso restringido.'], 403)
                : redirect()->route('customer-success.dashboard');
        }

        return $next($request);
    }
}
