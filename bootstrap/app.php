<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'admin.codigo' => \App\Http\Middleware\AdminCodigoVerificado::class,
         'solo.admin' => \App\Http\Middleware\SoloAdmin::class,
         'role'         => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Firma inválida o expirada: se muestra 404 en lugar de 403
        $exceptions->render(function (InvalidSignatureException $e, Request $request) {
        return response()->view('errors::404', [], 404);
        });
    })->create();
