<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web:
            __DIR__
            .'/../routes/web.php',

        commands:
            __DIR__
            .'/../routes/console.php',

        health:
            '/up',
    )
    ->withMiddleware(
        function (
            Middleware $middleware
        ): void {
            /*
            |--------------------------------------------------------------------------
            | MIDDLEWARE ALIASES
            |--------------------------------------------------------------------------
            |
            | Cho phép sử dụng:
            |
            | ->middleware('role:CUSTOMER')
            | ->middleware('role:STAFF,ADMIN')
            | ->middleware('role:TECHNICIAN')
            |
            */

            $middleware->alias([
                'role' =>
                    RoleMiddleware::class,
            ]);
        }
    )
    ->withExceptions(
        function (
            Exceptions $exceptions
        ): void {
            //
        }
    )
    ->create();