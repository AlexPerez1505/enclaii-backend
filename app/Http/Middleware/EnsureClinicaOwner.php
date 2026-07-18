<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicaOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->clinica_rol !== 'propietario') {
            abort(403, 'Solo el propietario de la clínica puede realizar esta acción.');
        }

        return $next($request);
    }
}
