<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsPasajero
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->rol !== 'pasajero') {
            return redirect()->route('dashboard')
                ->with('status', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
