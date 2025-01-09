<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $authData = [$loginType => $credentials['login'], 'password' => $credentials['password']];

        if (Auth::attempt($authData)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                Log::warning('Inactive user attempted to log in', ['user_id' => $user->id, 'email' => $user->email]);
                return back()->withErrors(['login' => 'Tu cuenta está desactivada.']);
            }

            Log::info('User logged in successfully', ['user_id' => $user->id, 'email' => $user->email, 'roles' => $this->getUserRoles($user)]);

            $request->session()->regenerate();

            if ($user->is_admin) {
                return redirect()->intended('/dashboard');
            } elseif ($user->is_seller) {
                return redirect()->intended('/sales');
            } elseif ($user->is_technician) {
                return redirect()->intended('/technical_services');
            } elseif ($user->can_add_products) {
                return redirect()->intended('/products');
            } else {
                return redirect()->intended('/');
            }
        }

        Log::warning('Failed login attempt', ['login' => $credentials['login']]);
        return back()->withErrors(['login' => 'Las credenciales proporcionadas no coinciden con nuestros registros.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
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

