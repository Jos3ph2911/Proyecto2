<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rides disponibles - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 1100px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; font-size: 13px; text-align: left; }
        th { background: #f0f0f0; }
        .filters { display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; }
        .filters input { padding:4px 6px; font-size:13px; }
        .btn { padding: 6px 10px; border-radius: 4px; text-decoration: none; border:none; cursor:pointer; font-size:13px; }
        .btn-primary { background: #1d4ed8; color:#fff; }
        .btn-secondary { background: #6b7280; color:#fff; }
        .btn-link { color:#1d4ed8; text-decoration: underline; font-size:13px; }
        .vehiculo-img { width: 80px; height: 60px; object-fit: cover; border-radius: 4px; display:block; }
        .status-msg { margin-top: 10px; color: green; }
        .badge-full { background:#dc2626; color:#fff; padding:2px 6px; border-radius:4px; font-size:11px; }
        .badge-info { background:#0ea5e9; color:#fff; padding:2px 6px; border-radius:4px; font-size:11px; }
        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <h1>Rides disponibles</h1>

        {{-- Zona derecha: según si hay usuario y rol --}}
        <div>
            @if ($user && $user->esPasajero())
                <a href="{{ route('reservations.passenger.index') }}" class="btn btn-secondary" style="margin-right:8px;">
                    Mis reservas
                </a>

                {{-- Aquí más adelante se podrá poner el icono de perfil --}}

                <form method="POST" action="{{ route('logout') }}" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        Cerrar sesión
                    </button>
                </form>
            @elseif ($user)
                {{-- Otro tipo de usuario logueado (admin/chofer) --}}
                <form method="POST" action="{{ route('logout') }}" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        Cerrar sesión
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar sesión</a>
            @endif
        </div>
    </div>

    {{-- Filtros de búsqueda --}}
    <form method="GET" action="{{ route('public.rides.index') }}">
        <div class="filters">
            <div>
                <label for="salida" style="font-size:12px; display:block;">Lugar de salida</label>
                <input type="text" id="salida" name="salida" value="{{ request('salida') }}">
            </div>

            <div>
                <label for="llegada" style="font-size:12px; display:block;">Lugar de llegada</label>
                <input type="text" id="llegada" name="llegada" value="{{ request('llegada') }}">
            </div>

            <div style="display:flex; align-items:flex-end; gap:6px;">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="{{ route('public.rides.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>
    </form>

    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

    @if ($rides->isEmpty())
        <p style="margin-top:15px;">No hay rides disponibles con los filtros seleccionados.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Foto</th>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Título</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Fecha y hora</th>
                <th>Costo x espacio</th>
                <th>Esp. disp.</th>
                <th>Chofer</th>
                <th>Acción</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($rides as $ride)
                @php
                    $reservasDelPasajero = collect();
                    $yaReservoEsteRide = false;

                    if ($user && $user->esPasajero() && isset($reservationsByRide[$ride->id])) {
                        $reservasDelPasajero = $reservationsByRide[$ride->id];
                        $yaReservoEsteRide = $reservasDelPasajero->isNotEmpty();
                    }
                @endphp

                <tr>
                    {{-- Foto --}}
                    <td>
                        @if ($ride->vehicle && $ride->vehicle->foto)
                            <img src="{{ asset('storage/' . $ride->vehicle->foto) }}"
                                 alt="Vehículo"
                                 class="vehiculo-img">
                        @else
                            <span style="font-size:12px; color:#777;">Sin foto</span>
                        @endif
                    </td>

                    {{-- Datos vehículo --}}
                    <td>{{ $ride->vehicle->placa ?? 'N/A' }}</td>
                    <td>{{ $ride->vehicle->marca ?? 'N/A' }}</td>
                    <td>{{ $ride->vehicle->modelo ?? 'N/A' }}</td>

                    {{-- Datos ride --}}
                    <td>{{ $ride->titulo }}</td>
                    <td>{{ $ride->lugar_salida }}</td>
                    <td>{{ $ride->lugar_llegada }}</td>
                    <td>{{ $ride->fecha_hora }}</td>
                    <td>{{ number_format($ride->costo_por_espacio, 2) }}</td>
                    <td>{{ $ride->espacios_disponibles }}</td>

                    {{-- Chofer --}}
                    <td>
                        @if ($ride->chofer)
                            {{ $ride->chofer->nombre }} {{ $ride->chofer->apellido }}
                        @else
                            N/A
                        @endif
                    </td>

                    {{-- Acción --}}
                    <td>
                        @if (!$user)
                            {{-- Invitado: al intentar reservar, lo mandamos a login --}}
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                Iniciar sesión para reservar
                            </a>
                        @elseif ($user->esPasajero())
                            @if ($ride->espacios_disponibles <= 0)
                                <span class="badge-full">Completo</span>
                            @elseif ($yaReservoEsteRide)
                                <span class="badge-info">Ya tienes una reserva en este ride</span>
                            @else
                                <form action="{{ route('reservations.store', $ride) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        Reservar
                                    </button>
                                </form>
                            @endif
                        @else
                            <span style="font-size:12px; color:#777;">Solo pasajeros pueden reservar</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    @endif

</div>
</body>
</html>
