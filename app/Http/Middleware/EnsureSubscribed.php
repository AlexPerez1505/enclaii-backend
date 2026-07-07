<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    /**
     * Rutas permitidas sin suscripción activa.
     */
    private array $allowedRoutes = [
        'configuracion',
        'configuracion.general.update',
        'stripe.checkout',
        'stripe.success',
        'stripe.cancel',
        'webhooks.stripe',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($user->hasRole('Customer Success')) {
            return $next($request);
        }

        if ($user->subscribed()) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if (in_array($routeName, $this->allowedRoutes, true)) {
            return $next($request);
        }

        return redirect()->route('configuracion')
            ->with('warning', 'Necesitas seleccionar un plan para acceder a EndoCare.');
    }
}
