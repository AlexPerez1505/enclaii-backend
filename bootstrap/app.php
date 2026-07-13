<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        $middleware->validateCsrfTokens(except: [
            'webhooks/whatsapp',
            'webhooks/stripe',
        ]);

        $middleware->web(\App\Http\Middleware\UserTimezone::class);

        $middleware->alias([
            'subscribed' => \App\Http\Middleware\EnsureSubscribed::class,
            'clinic.owner' => \App\Http\Middleware\EnsureClinicaOwner::class,
            'critical.password' => \App\Http\Middleware\RequireCriticalPassword::class,
<<<<<<< HEAD
            'customer.success' => \App\Http\Middleware\EnsureCustomerSuccess::class,
=======
>>>>>>> Ricardo-Galeria
        ]);

        // Usuarios ya autenticados que visitan /login o /registro:
        // si tienen plan -> dashboard, si no -> seleccionar plan
        $middleware->redirectUsersTo(function ($request) {
            return $request->user()?->subscribed() ? '/dashboard' : '/seleccionar-plan';
        });
    })
    ->withBroadcasting(
        channels: __DIR__.'/../routes/channels.php',
        attributes: ['middleware' => ['web', 'auth']],
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();