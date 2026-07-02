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
        if ($request->query->has('simulated_time')) {
            $val = $request->query('simulated_time');
            if ($val === 'clear' || empty($val)) {
                \Illuminate\Support\Facades\Cache::forget('simulated_time');
            } else {
                try {
                    $parsed = Carbon::parse($val);
                    \Illuminate\Support\Facades\Cache::forever('simulated_time', $parsed->toDateTimeString());
                } catch (\Exception $e) {
                    // Ignore invalid format
                }
            }
        }

        // 2. Set Carbon test now
        if (\Illuminate\Support\Facades\Cache::has('simulated_time')) {
            config(['session.lifetime' => 52560000]); // 100 years to prevent session/CSRF expiration
            Carbon::setTestNow(Carbon::parse(\Illuminate\Support\Facades\Cache::get('simulated_time')));
        } elseif ($fakeTime = env('APP_FAKE_TIME')) {
            config(['session.lifetime' => 52560000]);
            Carbon::setTestNow(Carbon::parse($fakeTime));
        }

        // 3. Share the updated timestamp with views for javascript clocks
        View::share('serverTimestampMs', Carbon::now()->timestamp * 1000);

        return $next($request);
    }
}
