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
    $middleware->alias([
        'no.cache' => \App\Http\Middleware\PreventCaching::class,
        'single.session' => \App\Http\Middleware\EnsureSingleSession::class,
    ]);
})
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (\Illuminate\Auth\AuthenticationException $exception, $request) {
        if ($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        return redirect()->guest(route('welcome')); // <-- Arahkan ke 'welcome'
    });
})->create();
