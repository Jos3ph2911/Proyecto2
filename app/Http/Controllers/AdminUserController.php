<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class AdminUserController extends Controller
{

    public function enviarRecordatoriosReservas(Request $request)
{
    // minutos de antigüedad de la reserva; si querés otro valor, cámbialo aquí
    $minutes = 1;

    // Ejecuta el comando Artisan que ya creamos
    Artisan::call('reservations:remind-pending', [
        'minutes' => $minutes,
    ]);

    // Mensaje para el admin
    return back()->with('status', "Se enviaron recordatorios a los choferes con reservas pendientes de más de {$minutes} minutos.");
}

    /**
     * Lista de usuarios (panel admin).
     */
    public function index()
    {
        // Todos los usuarios para la tabla principal
        $users = User::orderBy('id')->get();

        // Métricas generales
        $totalUsers      = User::count();
        $totalAdmins     = User::where('rol', 'administrador')->count();
        $totalChoferes   = User::where('rol', 'chofer')->count();
        $totalPasajeros  = User::where('rol', 'pasajero')->count();

        $totalActivos    = User::where('estado', 'ACTIVO')->count();
        $totalInactivos  = User::where('estado', 'INACTIVO')->count();
        $totalPendientes = User::where('estado', 'PENDIENTE')->count();

        // Viajes solo de choferes ACTIVO
        $rides = Ride::with(['chofer', 'vehicle'])
            ->whereHas('chofer', function ($q) {
                $q->where('estado', 'ACTIVO');
            })
            ->orderBy('fecha_hora', 'desc')
            ->get();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalAdmins',
            'totalChoferes',
            'totalPasajeros',
            'totalActivos',
            'totalInactivos',
            'totalPendientes',
            'rides'
        ));
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
     * Si un pasajero pasa a INACTIVO → liberar reservas.
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

        // 2. No desactivar la cuenta propia
        if ($currentUser->id === $user->id && $request->estado !== 'ACTIVO') {
            return back()->with('status', 'No puedes desactivar tu propia cuenta mientras estás conectado.');
        }

        // 3. Solo Super Admin puede modificar administradores
        if ($user->esAdministrador() && !$currentUser->esSuperAdmin()) {
            return back()->with('status', 'Solo el Super Admin puede cambiar el estado de otros administradores.');
        }

        // Guardar nuevo estado
        $user->estado = $request->estado;
        $user->save();

        /**
         * 🟦 NUEVA LÓGICA:
         * Si el usuario desactivado es PASAJERO → cancelar reservas y liberar espacios.
         */
        if ($user->esPasajero() && $user->estado === 'INACTIVO') {

            $reservas = $user->reservasComoPasajero()
                ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
                ->get();

            foreach ($reservas as $reserva) {

                $ride = $reserva->ride;

                // Si estaba ACEPTADA → devolver espacio
                if ($reserva->estado === 'ACEPTADA') {
                    $ride->espacios_disponibles += 1;
                    $ride->save();
                }

                // Marcar como cancelada
                $reserva->estado = 'CANCELADA';
                $reserva->save();
            }
        }

        return back()->with('status', 'Estado del usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario.
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        if ($user->esSuperAdmin()) {
            return back()->with('status', 'No se puede eliminar al Super Admin.');
        }

        if ($currentUser->id === $user->id) {
            return back()->with('status', 'No puedes eliminar tu propia cuenta.');
        }

        if ($user->esAdministrador() && !$currentUser->esSuperAdmin()) {
            return back()->with('status', 'Solo el Super Admin puede eliminar administradores.');
        }

        // Evitar que choferes con rides sean eliminados
if ($user->esChofer() && Ride::where('chofer_id', $user->id)->exists()) {
    return back()->with('status', 'No se puede eliminar este chofer porque tiene rides registrados.');
}

// Evitar que pasajeros con reservas sean eliminados
if ($user->esPasajero() && Reservation::where('user_id', $user->id)->exists()) {
    return back()->with('status', 'No se puede eliminar este pasajero porque tiene reservas activas.');
}

// Borrar usuario si es seguro hacerlo
$user->delete();

return back()->with('status', 'Usuario eliminado correctamente.');

    }
}
