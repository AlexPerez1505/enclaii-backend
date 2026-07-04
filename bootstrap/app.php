<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'webhooks/whatsapp',
            'webhooks/stripe',
        ]);

        $middleware->web(\App\Http\Middleware\UserTimezone::class);

        $middleware->alias([
            'subscribed' => \App\Http\Middleware\EnsureSubscribed::class,
            'clinic.owner' => \App\Http\Middleware\EnsureClinicaOwner::class,
            'critical.password' => \App\Http\Middleware\RequireCriticalPassword::class,
        ]);

        // Usuarios ya autenticados que visitan /login o /registro:
        // si tienen plan -> dashboard, si no -> seleccionar plan
        $middleware->redirectUsersTo(function ($request) {
            return $request->user()?->subscribed() ? '/dashboard' : '/seleccionar-plan';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
