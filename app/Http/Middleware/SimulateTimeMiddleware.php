<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class SimulateTimeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if simulated_time is passed as query parameter
        if ($request->has('simulated_time')) {
            $val = $request->query('simulated_time');
            if ($val === 'clear' || empty($val)) {
                session()->forget('simulated_time');
            } else {
                try {
                    $parsed = Carbon::parse($val);
                    session(['simulated_time' => $parsed->toDateTimeString()]);
                } catch (\Exception $e) {
                    // Ignore invalid format
                }
            }
        }

        // 2. Set Carbon test now
        if (session()->has('simulated_time')) {
            Carbon::setTestNow(Carbon::parse(session('simulated_time')));
        } elseif ($fakeTime = env('APP_FAKE_TIME')) {
            Carbon::setTestNow(Carbon::parse($fakeTime));
        }

        // 3. Share the updated timestamp with views for javascript clocks
        View::share('serverTimestampMs', Carbon::now()->timestamp * 1000);

        return $next($request);
    }
}
