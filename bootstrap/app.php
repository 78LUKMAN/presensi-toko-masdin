<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'simulate-time',
            'logout',
        ]);
        $middleware->web(prepend: [
            \App\Http\Middleware\SimulateTimeMiddleware::class,
        ], append: [
            \App\Http\Middleware\PreventBackHistory::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        $middleware->redirectGuestsTo(function (Illuminate\Http\Request $request) {
            if ($request->is('employee*')) {
                return route('employee.login');
            }
            return route('admin.login');
        });
        $middleware->redirectUsersTo(function (Illuminate\Http\Request $request) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                if (\Illuminate\Support\Facades\Auth::user()->role === 'employee') {
                    return route('employee.dashboard');
                }
                return route('admin.dashboard');
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 401 || $e->getStatusCode() === 403) {
                if ($request->is('employee*')) {
                    return redirect()->guest(route('employee.login'));
                }
                return redirect()->guest(route('admin.login'));
            }
        });
        
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('employee*')) {
                return redirect()->guest(route('employee.login'));
            }
            return redirect()->guest(route('admin.login'));
        });
    })->create();
