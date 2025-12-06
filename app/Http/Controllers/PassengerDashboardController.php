<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PassengerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user->esPasajero()) {
            abort(403);
        }

        $reservas = $user->reservasComoPasajero()
            ->with('ride')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $query = $user->reservasComoPasajero();

        $stats = [
            'totalReservations' => (clone $query)->count(),
            'activeReservations' => (clone $query)
                ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
                ->count(),
            'canceledReservations' => (clone $query)
                ->whereIn('estado', ['CANCELADA', 'RECHAZADA'])
                ->count(),
        ];

        return view('passenger.dashboard', compact('user', 'reservas', 'stats'));
    }
}
