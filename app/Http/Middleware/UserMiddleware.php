<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('UserMiddleware: Checking user permissions', [
            'user_id' => Auth::id(),
            'is_seller' => Auth::user()->is_seller ?? 'N/A',
            'is_admin' => Auth::user()->is_admin ?? 'N/A',
        ]);

        if (!Auth::check() || (!Auth::user()->is_seller && !Auth::user()->is_admin)) {
            Log::warning('UserMiddleware: Access denied', [
                'user_id' => Auth::id(),
                'url' => $request->fullUrl(),
            ]);
            return redirect()->route('login')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

