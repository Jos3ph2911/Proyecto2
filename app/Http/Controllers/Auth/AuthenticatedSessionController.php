<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar formulario de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Manejar envío de formulario de login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Verificar estado de la cuenta
        if ($user->estado !== 'ACTIVO') {
            $estado = strtolower($user->estado);

            Auth::logout();

            return back()->withErrors([
                'email' => "Tu cuenta está en estado {$estado}. Debes activarla para poder ingresar.",
            ])->onlyInput('email');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Cerrar sesión.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
