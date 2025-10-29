<?php

use App\Http\Middleware\RequestContext;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/user_route.php',
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(RequestContext::class);
        $middleware->alias([
            'role' =>  \Spatie\Permission\Middleware\RoleMiddleware::class,
            'jadwal.rap' => \App\Http\Middleware\CheckRapSchedule::class,
            'jadwal.monev' => \App\Http\Middleware\CheckMonevSchedule::class,
            'jadwal.rakortek' => \App\Http\Middleware\Jadwal\Cek\CekJadwalRakortek::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/test',
            '/test/new_schedule',
            '/test/get_schedule',
            '/test/update_schedule',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
