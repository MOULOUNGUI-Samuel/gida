<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Build the application using the Laravel 11 configuration helpers.
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'redirect.user.type' => \App\Http\Middleware\RedirectBasedOnUserType::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Ensure the application uses the app-specific HTTP Kernel so route middleware
// (declared in `App\Http\Kernel`) are available at runtime.
$app->singleton(Illuminate\Contracts\Http\Kernel::class, \App\Http\Kernel::class);

return $app;
