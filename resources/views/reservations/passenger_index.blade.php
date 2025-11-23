<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis reservas - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 1000px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; font-size: 13px; text-align:left; }
        th { background:#f0f0f0; }
        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
        .btn-link { color:#1d4ed8; text-decoration:underline; font-size:13px; }
        .btn { padding: 6px 10px; border-radius: 4px; border:none; cursor:pointer; font-size:13px; }
        .btn-cancel { background:#dc2626; color:#fff; }
        .badge { padding:2px 6px; border-radius:4px; font-size:11px; }
        .badge-pendiente { background:#f59e0b; color:#000; }
        .badge-aceptada { background:#16a34a; color:#fff; }
        .badge-rechazada { background:#dc2626; color:#fff; }
        .badge-cancelada { background:#6b7280; color:#fff; }
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <h1>Mis reservas</h1>

        {{-- Volver al panel principal del pasajero: rides disponibles --}}
        <a href="{{ route('public.rides.index') }}" class="btn-link">
            ← Volver a rides disponibles
        </a>
    </div>

    @if (session('status'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('status') }}
        </div>
    @endif

    @if ($reservations->isEmpty())
        <p>No tienes reservas registradas.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Ride</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Fecha y hora</th>
                <th>Chofer</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->ride->titulo }}</td>
                    <td>{{ $reservation->ride->lugar_salida }}</td>
                    <td>{{ $reservation->ride->lugar_llegada }}</td>
                    <td>{{ $reservation->ride->fecha_hora }}</td>
                    <td>
                        @if ($reservation->ride->chofer)
                            {{ $reservation->ride->chofer->nombre }} {{ $reservation->ride->chofer->apellido }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @switch($reservation->estado)
                            @case('PENDIENTE')
                                <span class="badge badge-pendiente">PENDIENTE</span>
                                @break
                            @case('ACEPTADA')
                                <span class="badge badge-aceptada">ACEPTADA</span>
                                @break
                            @case('RECHAZADA')
                                <span class="badge badge-rechazada">RECHAZADA</span>
                                @break
                            @case('CANCELADA')
                                <span class="badge badge-cancelada">CANCELADA</span>
                                @break
                            @default
                                {{ $reservation->estado }}
                        @endswitch
                    </td>
                    <td>
                        @if (in_array($reservation->estado, ['PENDIENTE', 'ACEPTADA']))
                            <form action="{{ route('reservations.cancel', $reservation) }}" method="POST"
                                  onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                                @csrf
                                <button type="submit" class="btn btn-cancel">
                                    Cancelar
                                </button>
                            </form>
                        @else
                            <span style="font-size:12px; color:#777;">Sin acciones</span>
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
