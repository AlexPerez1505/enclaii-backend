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
        $middleware->validateCsrfTokens(except: [
            'webhooks/whatsapp',
            'webhooks/stripe',
            'broadcasting/auth',
        ]);

        $middleware->alias([
            'subscribed' => \App\Http\Middleware\EnsureSubscribed::class,
            'customer.success' => \App\Http\Middleware\EnsureCustomerSuccess::class,
        ]);

        // Usuarios ya autenticados que visitan /login o /registro:
        // customer success -> dashboard propio, suscritos -> dashboard, resto -> seleccionar plan
        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();
            if (!$user) {
                return '/login';
            }
            if ($user->hasRole('Customer Success')) {
                return '/customer-success/dashboard';
            }
            return $user->subscribed() ? '/dashboard' : '/configuracion';
        });
    })
    ->withBroadcasting(
        channels: __DIR__.'/../routes/channels.php',
        attributes: ['middleware' => ['web', 'auth']],
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
