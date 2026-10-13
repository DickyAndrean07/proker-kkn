<?php

use App\Http\Middleware\EnsureAdminAccountActive;
use App\Http\Middleware\EnsureRole;
use App\Models\AdminAccount;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.active' => EnsureAdminAccountActive::class,
            'role' => EnsureRole::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        $middleware->redirectUsersTo(function () {
            $user = Auth::user();

            if ($user instanceof AdminAccount && $user->isAdminDusun()) {
                return route('admin-dusun.dashboard');
            }

            if ($user instanceof AdminAccount && $user->isSuperAdmin()) {
                return route('super-admin.dashboard');
            }

            return route('admin.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

$storagePath = env('APP_STORAGE', '/tmp/storage');
if (getenv('VERCEL') || isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL']) || env('APP_STORAGE') || !is_writable(storage_path())) {
    $app->useStoragePath($storagePath);
}

return $app;
