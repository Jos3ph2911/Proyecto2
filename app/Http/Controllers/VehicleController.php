<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Mostrar listado de vehículos del chofer autenticado.
     */
    public function index()
    {
        $user = Auth::user();

        $vehicles = Vehicle::where('user_id', $user->id)->get();

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Mostrar formulario para crear vehículo.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Guardar nuevo vehículo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'placa'     => ['required', 'string', 'max:20', 'unique:vehicles,placa'],
            'color'     => ['nullable', 'string', 'max:50'],
            'marca'     => ['nullable', 'string', 'max:50'],
            'modelo'    => ['nullable', 'string', 'max:50'],
            'anio'      => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'capacidad' => ['required', 'integer', 'min:1'],
            'foto'      => ['nullable', 'image', 'max:2048'],
        ]);

        $user = Auth::user();

        $rutaFoto = null;
        if ($request->hasFile('foto')) {
            // Guarda en storage/app/public/vehiculos
            $rutaFoto = $request->file('foto')->store('vehiculos', 'public');
        }

        Vehicle::create([
            'user_id'   => $user->id,
            'placa'     => $request->placa,
            'color'     => $request->color,
            'marca'     => $request->marca,
            'modelo'    => $request->modelo,
            'anio'      => $request->anio,
            'capacidad' => $request->capacidad,
            'foto'      => $rutaFoto,
        ]);

        return redirect()->route('vehicles.index')
            ->with('status', 'Vehículo registrado correctamente.');
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);

        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Actualizar vehículo.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);

        $request->validate([
            'placa'     => ['required', 'string', 'max:20', 'unique:vehicles,placa,' . $vehicle->id],
            'color'     => ['nullable', 'string', 'max:50'],
            'marca'     => ['nullable', 'string', 'max:50'],
            'modelo'    => ['nullable', 'string', 'max:50'],
            'anio'      => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'capacidad' => ['required', 'integer', 'min:1'],
            'foto'      => ['nullable', 'image', 'max:2048'],
        ]);

        $rutaFoto = $vehicle->foto;

        if ($request->hasFile('foto')) {
            // Borrar foto anterior si existe
            if ($rutaFoto && Storage::disk('public')->exists($rutaFoto)) {
                Storage::disk('public')->delete($rutaFoto);
            }
            $rutaFoto = $request->file('foto')->store('vehiculos', 'public');
        }

        $vehicle->update([
            'placa'     => $request->placa,
            'color'     => $request->color,
            'marca'     => $request->marca,
            'modelo'    => $request->modelo,
            'anio'      => $request->anio,
            'capacidad' => $request->capacidad,
            'foto'      => $rutaFoto,
        ]);

        return redirect()->route('vehicles.index')
            ->with('status', 'Vehículo actualizado correctamente.');
    }

    /**
     * Eliminar vehículo.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);

        // Borrar foto si existe
        if ($vehicle->foto && Storage::disk('public')->exists($vehicle->foto)) {
            Storage::disk('public')->delete($vehicle->foto);
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('status', 'Vehículo eliminado correctamente.');
    }

    /**
     * Verificar que el vehículo pertenece al usuario autenticado.
     */
    protected function authorizeOwner(Vehicle $vehicle): void
    {
        $user = Auth::user();

        if ($vehicle->user_id !== $user->id) {
            abort(403, 'No tienes permiso para editar este vehículo.');
        }
    }
}
