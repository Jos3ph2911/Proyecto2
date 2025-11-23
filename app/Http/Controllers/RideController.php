<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{
    /**
     * Listar rides del chofer autenticado.
     */
    public function index()
    {
        $user = Auth::user();

        $rides = Ride::with('vehicle')
            ->where('chofer_id', $user->id)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return view('rides.index', compact('rides'));
    }

    /**
     * Mostrar formulario para crear un ride.
     */
    public function create()
    {
        $user = Auth::user();

        // Vehículos del chofer
        $vehicles = Vehicle::where('user_id', $user->id)->get();

        if ($vehicles->isEmpty()) {
            return redirect()->route('vehicles.index')
                ->with('status', 'Primero debes registrar al menos un vehículo para poder crear rides.');
        }

        return view('rides.create', compact('vehicles'));
    }

    /**
     * Guardar un nuevo ride.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'vehicle_id'        => ['required', 'integer', 'exists:vehicles,id'],
            'titulo'            => ['required', 'string', 'max:100'],
            'lugar_salida'      => ['required', 'string', 'max:100'],
            'lugar_llegada'     => ['required', 'string', 'max:100'],
            'fecha_hora'        => ['required', 'date'],
            'costo_por_espacio' => ['required', 'numeric', 'min:0'],
            'espacios_totales'  => ['required', 'integer', 'min:1'],
        ]);

        // Verificar que el vehículo sea del chofer autenticado
        $vehicle = Vehicle::where('id', $request->vehicle_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Máximo de pasajeros = capacidad - 1 (un asiento es del chofer)
        $maxPasajeros = $vehicle->capacidad - 1;

    if ($request->espacios_totales > $maxPasajeros) {
        return back()
            ->withErrors([
                'espacios_totales' =>
                    'La cantidad de espacios ingresada excede la capacidad permitida para pasajeros en este vehículo.',
            ])
            ->withInput();
    }


        Ride::create([
            'chofer_id'            => $user->id,
            'vehicle_id'           => $vehicle->id,
            'titulo'               => $request->titulo,
            'lugar_salida'         => $request->lugar_salida,
            'lugar_llegada'        => $request->lugar_llegada,
            'fecha_hora'           => $request->fecha_hora,
            'costo_por_espacio'    => $request->costo_por_espacio,
            'espacios_totales'     => $request->espacios_totales,
            'espacios_disponibles' => $request->espacios_totales,
        ]);

        return redirect()->route('rides.index')
            ->with('status', 'Ride creado correctamente.');
    }

    /**
     * Mostrar formulario para editar un ride.
     */
    public function edit(Ride $ride)
    {
        $this->authorizeOwner($ride);

        $user = Auth::user();
        $vehicles = Vehicle::where('user_id', $user->id)->get();

        return view('rides.edit', compact('ride', 'vehicles'));
    }

    /**
     * Actualizar un ride.
     */
    public function update(Request $request, Ride $ride)
    {
        $this->authorizeOwner($ride);

        $user = Auth::user();

        $request->validate([
            'vehicle_id'        => ['required', 'integer', 'exists:vehicles,id'],
            'titulo'            => ['required', 'string', 'max:100'],
            'lugar_salida'      => ['required', 'string', 'max:100'],
            'lugar_llegada'     => ['required', 'string', 'max:100'],
            'fecha_hora'        => ['required', 'date'],
            'costo_por_espacio' => ['required', 'numeric', 'min:0'],
            'espacios_totales'  => ['required', 'integer', 'min:1'],
        ]);

        // Verificar que el vehículo pertenezca al chofer
        $vehicle = Vehicle::where('id', $request->vehicle_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Máximo de pasajeros = capacidad - 1
        $maxPasajeros = $vehicle->capacidad - 1;

        if ($request->espacios_totales > $maxPasajeros) {
            return back()
                ->withErrors([
                    'espacios_totales' =>
                        'La cantidad de espacios ingresada excede la capacidad permitida para pasajeros en este vehículo.',
                ])
                ->withInput();
        }


        // Más adelante ajustaremos espacios_disponibles según reservas
        $ride->update([
            'vehicle_id'           => $vehicle->id,
            'titulo'               => $request->titulo,
            'lugar_salida'         => $request->lugar_salida,
            'lugar_llegada'        => $request->lugar_llegada,
            'fecha_hora'           => $request->fecha_hora,
            'costo_por_espacio'    => $request->costo_por_espacio,
            'espacios_totales'     => $request->espacios_totales,
            'espacios_disponibles' => $request->espacios_totales,
        ]);

        return redirect()->route('rides.index')
            ->with('status', 'Ride actualizado correctamente.');
    }

    /**
     * Eliminar un ride.
     */
    public function destroy(Ride $ride)
    {
        $this->authorizeOwner($ride);

        $ride->delete();

        return redirect()->route('rides.index')
            ->with('status', 'Ride eliminado correctamente.');
    }

    /**
     * Verificar que el ride pertenece al chofer autenticado.
     */
    protected function authorizeOwner(Ride $ride): void
    {
        $user = Auth::user();

        if ($ride->chofer_id !== $user->id) {
            abort(403, 'No tienes permiso para gestionar este ride.');
        }
    }
}
