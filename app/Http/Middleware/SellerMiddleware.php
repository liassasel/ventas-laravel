<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->is_seller) {
            return redirect()->route('login')->with('error', 'Acceso no autorizado.');
        }

        return $next($request);
    }
}

