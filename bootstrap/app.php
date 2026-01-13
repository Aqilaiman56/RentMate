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
        $middleware->trustProxies(at: '*');
        $middleware->trustHosts(at: [
            'localhost',
    	    '152.42.239.115',
            'gorentums.me',
            'www.gorentums.me',
            '*.ngrok-free.dev',
        ]);

        // Exclude payment callback from CSRF verification (external API callback)
        $middleware->validateCsrfTokens(except: [
            'payment/callback',
        ]);

        // Add CheckSuspension middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\CheckSuspension::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
