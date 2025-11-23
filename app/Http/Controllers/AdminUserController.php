<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Lista de usuarios (panel admin).
     */
    public function index()
    {
        $currentUser = Auth::user();

        $users = User::orderBy('rol')
            ->orderBy('nombre')
            ->get();

        return view('admin.users.index', compact('users', 'currentUser'));
    }

    /**
     * Mostrar formulario para crear un nuevo administrador.
     */
    public function create()
    {
        $currentUser = Auth::user();

        return view('admin.users.create', compact('currentUser'));
    }

    /**
     * Guardar nuevo administrador creado desde el panel.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        // Cualquier admin (incluyendo super admin) puede crear admins
        if (!$currentUser->esAdministrador()) {
            abort(403, 'No tienes permiso para crear administradores.');
        }

        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:50'],
            'apellido'         => ['required', 'string', 'max:50'],
            'cedula'           => ['required', 'string', 'max:20', 'unique:users,cedula'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'email'            => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'nombre'           => $data['nombre'],
            'apellido'         => $data['apellido'],
            'cedula'           => $data['cedula'],
            'fecha_nacimiento' => $data['fecha_nacimiento'] ?? null,
            'telefono'         => $data['telefono'] ?? null,
            'foto'             => null,
            'email'            => $data['email'],
            'password'         => Hash::make($data['password']),
            'rol'              => 'administrador',
            'estado'           => 'ACTIVO',
            'token_activacion' => null,
            'is_super_admin'   => false,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Administrador creado correctamente.');
    }

    /**
     * Cambiar estado (PENDIENTE / ACTIVO / INACTIVO).
     * Solo SUPER ADMIN puede cambiar estado de administradores.
     * Cualquier admin puede cambiar estado de choferes/pasajeros.
     */
    public function updateStatus(Request $request, User $user)
    {
        $currentUser = Auth::user();

        $request->validate([
            'estado' => ['required', 'in:PENDIENTE,ACTIVO,INACTIVO'],
        ]);

        // 1. No tocar al Super Admin
        if ($user->esSuperAdmin()) {
            return back()->with('status', 'No se puede modificar el estado del Super Admin.');
        }

        // 2. No desactivar tu propia cuenta
        if ($currentUser->id === $user->id && $request->estado !== 'ACTIVO') {
            return back()->with('status', 'No puedes desactivar tu propia cuenta mientras estás conectado.');
        }

        // 3. Solo Super Admin puede cambiar estado de administradores
        if ($user->esAdministrador() && !$currentUser->esSuperAdmin()) {
            return back()->with('status', 'Solo el Super Admin puede cambiar el estado de otros administradores.');
        }

        $user->estado = $request->estado;
        $user->save();

        return back()->with('status', 'Estado del usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario.
     * Solo SUPER ADMIN puede eliminar admins.
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // 1. No borrar al Super Admin
        if ($user->esSuperAdmin()) {
            return back()->with('status', 'No se puede eliminar al Super Admin.');
        }

        // 2. No borrar tu propia cuenta
        if ($currentUser->id === $user->id) {
            return back()->with('status', 'No puedes eliminar tu propia cuenta.');
        }

        // 3. Solo el Super Admin puede borrar administradores
        if ($user->esAdministrador() && !$currentUser->esSuperAdmin()) {
            return back()->with('status', 'Solo el Super Admin puede eliminar cuentas de administradores.');
        }

        $user->delete();

        return back()->with('status', 'Usuario eliminado correctamente.');
    }
}
