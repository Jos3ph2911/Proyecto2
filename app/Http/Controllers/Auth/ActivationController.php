<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class ActivationController extends Controller
{
    /**
     * Activar cuenta usando el token enviado por correo.
     */
    public function activar(string $token)
    {
        // Buscar usuario que tenga este token
        $user = User::where('token_activacion', $token)->first();

        // Si no existe, token inválido
        if (! $user) {
            return redirect()->route('login')->withErrors([
                'email' => 'Este enlace de activación no es válido o ya fue usado.',
            ]);
        }

        // Si ya estaba activo
        if ($user->estado === 'ACTIVO') {
            return redirect()->route('login')->with(
                'status',
                'Tu cuenta ya estaba activa. Ya podés iniciar sesión.'
            );
        }

        // Activar la cuenta
        $user->estado = 'ACTIVO';
        $user->token_activacion = null; // Eliminar token para que no se pueda usar otra vez
        $user->save();

        return redirect()->route('login')->with(
            'status',
            'Tu cuenta ha sido activada correctamente. Ya podés iniciar sesión.'
        );
    }
}
