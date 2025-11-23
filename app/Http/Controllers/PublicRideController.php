<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicRideController extends Controller
{
    public function index(Request $request)
    {
        $query = Ride::with(['vehicle', 'chofer'])
            ->where('fecha_hora', '>=', now())
            ->orderBy('fecha_hora', 'asc');

        // Filtros simples por origen/destino
        if ($request->filled('salida')) {
            $query->where('lugar_salida', 'like', '%' . $request->salida . '%');
        }

        if ($request->filled('llegada')) {
            $query->where('lugar_llegada', 'like', '%' . $request->llegada . '%');
        }

        $rides = $query->get();

        $user = Auth::user();

        return view('public_rides.index', compact('rides', 'user'));
    }
}
