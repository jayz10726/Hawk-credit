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
    ->withMiddleware(function (Middleware $middleware) {
        // --- Single consolidated middleware block ---
        
        // Add to web group
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\EnsureUserIsActive::class,
            \App\Http\Middleware\EnsureOrgIsActive::class,
            \App\Http\Middleware\ScopeTenantData::class,
        ]);

        // Add to api group
        $middleware->appendToGroup('api', [
            \App\Http\Middleware\EnsureUserIsActive::class,
            \App\Http\Middleware\ScopeTenantData::class,
        ]);

        // Named aliases for use in routes
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'perm' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
