<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsChofer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si no está logueado o no es chofer, lo sacamos
        if (!$user || $user->rol !== 'chofer') {
            return redirect()->route('dashboard')
                ->with('status', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
