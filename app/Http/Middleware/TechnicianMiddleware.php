<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicianMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || (!Auth::user()->is_technician && !Auth::user()->is_admin)) {
            return redirect()->route('login')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

