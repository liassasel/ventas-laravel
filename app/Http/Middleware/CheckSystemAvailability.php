<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSystemAvailability
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Allow admins to access at any time
        if ($user && $user->is_admin) {
            return $next($request);
        }

        // For non-admin users, check system availability
        if ($user && !$user->is_admin) {
            $now = Carbon::now();
            
            // Check if it's Sunday
            if ($now->isSunday()) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'El sistema no está disponible los domingos.');
            }

            // Check if current time is between 8 AM and 7 PM
            $startTime = Carbon::createFromTimeString('08:00:00');
            $endTime = Carbon::createFromTimeString('19:00:00');

            if (!$now->between($startTime, $endTime)) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'El sistema solo está disponible de 8:00 AM a 7:00 PM.');
            }
        }

        return $next($request);
    }
}

