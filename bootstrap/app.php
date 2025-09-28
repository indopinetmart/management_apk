<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',   // ✅ tambahkan ini
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 🔹 Daftar alias middleware khusus
        $middleware->alias([
            'check.session' => \App\Http\Middleware\CheckUserSession::class,
            'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
            'check.profile' => \App\Http\Middleware\CheckUserProfile::class,
            'ip.trusted'=>\App\Http\Middleware\TrustProxies::class,
        ]);

        // Middleware API (opsional, misal binding, throttle)
        $middleware->api([
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
