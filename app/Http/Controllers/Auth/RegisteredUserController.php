<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivarCuentaMail;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar vista de registro.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Manejar envío de formulario de registro.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => ['required', 'string', 'max:255'],
            'apellido'         => ['required', 'string', 'max:255'],
            'cedula'           => ['required', 'string', 'max:20', 'unique:users,cedula'],
            'fecha_nacimiento' => ['required', 'date'],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'rol'              => ['required', 'in:chofer,pasajero'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nombre'           => $request->nombre,
            'apellido'         => $request->apellido,
            'cedula'           => $request->cedula,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono'         => $request->telefono,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'rol'              => $request->rol,
            // Por ahora se crea como PENDIENTE, luego haremos la activación por correo:
            'estado'           => 'PENDIENTE',
            'token_activacion' => Str::random(64),
        ]);

        Mail::to($user->email)->send(new ActivarCuentaMail($user));


        event(new Registered($user));

        // IMPORTANTE: NO lo logueamos automáticamente,
        // porque debe activar su cuenta primero.
        // Auth::login($user);

        return redirect()->route('login')->with('status', 'Registro realizado. Tu cuenta está pendiente de activación.');
    }
}
