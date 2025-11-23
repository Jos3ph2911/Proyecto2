<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicRideController extends Controller
{
    public function index(Request $request)
    {
        $query = Ride::with(['vehicle', 'chofer'])
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora', 'asc');

        // Filtros por origen/destino
        if ($request->filled('salida')) {
            $query->where('lugar_salida', 'like', '%' . $request->salida . '%');
        }

        if ($request->filled('llegada')) {
            $query->where('lugar_llegada', 'like', '%' . $request->llegada . '%');
        }

        $rides = $query->get();

        $user = Auth::user();
        $reservationsByRide = [];

        // Si es pasajero, cargamos sus reservas PENDIENTE/ACEPTADA agrupadas por ride
        if ($user && $user->esPasajero()) {
            $reservations = Reservation::where('pasajero_id', $user->id)
                ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
                ->get();

            $reservationsByRide = $reservations->groupBy('ride_id');
        }

        return view('public_rides.index', [
            'rides'             => $rides,
            'user'              => $user,
            'reservationsByRide'=> $reservationsByRide,
        ]);
    }
}
