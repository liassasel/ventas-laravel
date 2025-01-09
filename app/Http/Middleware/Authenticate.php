<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->authenticate($request, $guards) === 'authentication_failed') {
            return $this->redirectTo($request);
        }

        if (Auth::check()) {
            $user = Auth::user();
            Log::info('User accessed protected route', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $this->getUserRoles($user),
                'route' => $request->route()->getName(),
            ]);
        }

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void|string
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        Log::warning('Unauthenticated access attempt', [
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
        ]);

        return 'authentication_failed';
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): string
    {
        Log::info('Unauthenticated user redirected to login', [
            'ip' => $request->ip(),
            'intended_url' => $request->fullUrl(),
        ]);
        return $request->expectsJson() ? '' : route('login');
    }

    private function getUserRoles($user)
    {
        $roles = [];
        if ($user->is_admin) $roles[] = 'admin';
        if ($user->is_seller) $roles[] = 'seller';
        if ($user->is_technician) $roles[] = 'technician';
        if ($user->can_add_products) $roles[] = 'can_add_products';
        return $roles;
    }
}

