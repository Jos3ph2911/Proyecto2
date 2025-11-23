<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverReservationController extends Controller
{
    /**
     * Mostrar reservas de un ride del chofer autenticado.
     */
    public function index(Ride $ride)
    {
        $user = Auth::user();

        // Validar que el ride pertenece al chofer autenticado
        if ($ride->chofer_id !== $user->id) {
            abort(403, 'No tienes permiso para ver estas reservas.');
        }

        // Recalcular espacios por si algo quedó desfasado
        $this->updateRideSpaces($ride);
        $ride->refresh();

        $reservations = $ride->reservations()
            ->with('pasajero')
            ->orderBy('fecha_reserva', 'asc')
            ->get();

        return view('driver_reservations.index', compact('ride', 'reservations'));
    }

    /**
     * Aceptar una reserva.
     */
    public function accept(Reservation $reservation)
    {
        $ride = $reservation->ride;
        $user = Auth::user();

        if ($ride->chofer_id !== $user->id) {
            abort(403, 'No tienes permiso.');
        }

        $reservation->estado = 'ACEPTADA';
        $reservation->save();

        $this->updateRideSpaces($ride);

        return back()->with('status', 'Reserva aceptada.');
    }

    /**
     * Rechazar una reserva.
     */
    public function reject(Reservation $reservation)
    {
        $ride = $reservation->ride;
        $user = Auth::user();

        if ($ride->chofer_id !== $user->id) {
            abort(403, 'No tienes permiso.');
        }

        $reservation->estado = 'RECHAZADA';
        $reservation->save();

        $this->updateRideSpaces($ride);

        return back()->with('status', 'Reserva rechazada.');
    }

    /**
     * Cancelar una reserva ya aceptada (por el chofer).
     */
    public function cancelByDriver(Reservation $reservation)
    {
        $ride = $reservation->ride;
        $user = Auth::user();

        if ($ride->chofer_id !== $user->id) {
            abort(403, 'No tienes permiso.');
        }

        // Cambiar estado a cancelada
        $reservation->estado = 'CANCELADA';
        $reservation->save();

        // Recalcular espacios disponibles
        $this->updateRideSpaces($ride);

        return back()->with('status', 'Reserva cancelada por el chofer.');
    }

    /**
     * Recalcular espacios disponibles del ride.
     */
    protected function updateRideSpaces(Ride $ride): void
    {
        $ride->load('vehicle');

        $vehicle = $ride->vehicle;
        if (!$vehicle) {
            return;
        }

        // Máximo pasajeros = capacidad - 1 (asiento del chofer)
        $maxPasajeros = max(0, $vehicle->capacidad - 1);

        // Cuántos asientos se ofrecen en el ride (por diseño no debería superar maxPasajeros)
        $capacidadRide = min($ride->espacios_totales, $maxPasajeros);

        // Reservas PENDIENTE o ACEPTADA cuentan como ocupadas
        $ocupados = $ride->reservations()
            ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
            ->count();

        $ride->espacios_disponibles = max(0, $capacidadRide - $ocupados);
        $ride->save();
    }
}
