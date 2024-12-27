<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validar los datos de entrada
        $credentials = $request->validate([
            'login' => 'required|string', // Puede ser username o email
            'password' => 'required|string',
        ]);

        // Intentar autenticación usando email o username
        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // Reemplazar 'login' por el campo detectado (email o username)
        $authData = [$loginType => $credentials['login'], 'password' => $credentials['password']];

        if (Auth::attempt($authData)) {
            $user = Auth::user();

            // Verificar si el usuario está activo
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['login' => 'Tu cuenta está desactivada.']);
            }

            // Redirigir al usuario a la página principal o dashboard
            return redirect()->intended('/');
        }

        // Si la autenticación falla
        return back()->withErrors(['login' => 'Las credenciales proporcionadas no coinciden con nuestros registros.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function deactivateNonAdmins()
    {
        if (!Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        User::where('is_admin', false)->update(['is_active' => false]);
        return redirect()->back()->with('success', 'Usuarios no administradores desactivados.');
    }
}