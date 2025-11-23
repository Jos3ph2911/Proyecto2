<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Crear una reserva para un ride (pasajero).
     */
    public function store(Ride $ride, Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->rol !== 'pasajero') {
            return redirect()->route('login')
                ->with('status', 'Debes iniciar sesión como pasajero para reservar.');
        }

        // Evitar reservar rides pasados
        if ($ride->fecha_hora < now()) {
            return back()->withErrors(['general' => 'No es posible reservar un ride en el pasado.']);
        }

        $ride->load('vehicle');

        if (!$ride->vehicle) {
            return back()->withErrors(['general' => 'El ride no tiene un vehículo asociado.'])->withInput();
        }

        $vehicle = $ride->vehicle;

        // Capacidad máxima de pasajeros = capacidad vehículo - 1
        $maxPasajeros = max(0, $vehicle->capacidad - 1);

        // Total de asientos que se ofrecen en este ride (no puede ser mayor que maxPasajeros)
        $capacidadRide = min($ride->espacios_totales, $maxPasajeros);

        // Reservas ocupadas (pendientes o aceptadas)
        $ocupados = Reservation::where('ride_id', $ride->id)
            ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
            ->count();

        if ($ocupados >= $capacidadRide) {
            return back()->withErrors(['general' => 'No hay espacios disponibles en este ride.']);
        }

        // Evitar reservas duplicadas del mismo pasajero en el mismo ride
        $yaReservo = Reservation::where('ride_id', $ride->id)
            ->where('pasajero_id', $user->id)
            ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
            ->exists();

        if ($yaReservo) {
            return back()->withErrors(['general' => 'Ya tienes una reserva activa para este ride.']);
        }

        // Crear reserva en estado PENDIENTE
        Reservation::create([
            'ride_id'      => $ride->id,
            'pasajero_id'  => $user->id,
            'estado'       => 'PENDIENTE',
            'fecha_reserva'=> now(),
        ]);

        // Actualizar espacios_disponibles del ride (informativo)
        $ocupadosActual = Reservation::where('ride_id', $ride->id)
            ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
            ->count();

        $ride->espacios_disponibles = max(0, $capacidadRide - $ocupadosActual);
        $ride->save();

        return redirect()->route('reservations.passenger.index')
            ->with('status', 'Reserva creada correctamente. Queda en estado PENDIENTE.');
    }

    /**
     * Listado de reservas del pasajero autenticado.
     */
    public function indexPassenger()
    {
        $user = Auth::user();

        $reservations = Reservation::with(['ride.vehicle', 'ride.chofer'])
            ->where('pasajero_id', $user->id)
            ->orderBy('fecha_reserva', 'desc')
            ->get();

        return view('reservations.passenger_index', compact('reservations'));
    }

    /**
     * Cancelar una reserva (pasajero).
     */
    public function cancel(Reservation $reservation)
    {
        $user = Auth::user();

        if ($reservation->pasajero_id !== $user->id) {
            abort(403, 'No puedes cancelar esta reserva.');
        }

        if ($reservation->estado === 'CANCELADA') {
            return back()->with('status', 'Esta reserva ya estaba cancelada.');
        }

        $reservation->estado = 'CANCELADA';
        $reservation->save();

        // Recalcular espacios disponibles del ride
        $ride = $reservation->ride()->with('vehicle')->first();
        if ($ride && $ride->vehicle) {
            $vehicle = $ride->vehicle;
            $maxPasajeros = max(0, $vehicle->capacidad - 1);
            $capacidadRide = min($ride->espacios_totales, $maxPasajeros);

            $ocupados = $ride->reservations()
                ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
                ->count();

            $ride->espacios_disponibles = max(0, $capacidadRide - $ocupados);
            $ride->save();
        }

        return back()->with('status', 'Reserva cancelada correctamente.');
    }
}
