<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanAddProductsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || (!Auth::user()->can_add_products && !Auth::user()->is_admin)) {
            return redirect()->route('products.index')
                ->with('error', 'No tienes permisos para agregar productos.');
        }

        return $next($request);
    }
}

