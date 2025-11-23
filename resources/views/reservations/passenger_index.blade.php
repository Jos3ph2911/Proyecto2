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
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; font-size: 13px; }
        th { background: #f0f0f0; }
        .vehiculo-img { width: 70px; height: 50px; object-fit: cover; border-radius: 4px; display: block; }
        .btn { display: inline-block; padding: 5px 9px; font-size: 13px; text-decoration: none; border-radius: 4px; }
        .btn-danger { background: #dc2626; color: #fff; border: none; cursor: pointer; }
        .btn-disabled { background: #9ca3af; color: #fff; border: none; cursor: not-allowed; }
        .status-ok { color: green; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Mis reservas</h1>

    <a href="{{ route('public.rides.index') }}" class="btn">Ver rides disponibles</a>

    @if (session('status'))
        <div class="status-ok">
            {{ session('status') }}
        </div>
    @endif

    @if ($reservations->isEmpty())
        <p>No tienes reservas registradas.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Foto</th>
                <th>Placa</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Fecha ride</th>
                <th>Estado reserva</th>
                <th>Chofer</th>
                <th>Fecha reserva</th>
                <th>Acción</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($reservations as $reservation)
                @php
                    $ride = $reservation->ride;
                    $vehicle = $ride?->vehicle;
                @endphp
                <tr>
                    <td>
                        @if ($vehicle && $vehicle->foto)
                            <img src="{{ asset('storage/' . $vehicle->foto) }}"
                                 alt="Vehículo"
                                 class="vehiculo-img">
                        @else
                            <span style="font-size:12px; color:#777;">Sin foto</span>
                        @endif
                    </td>
                    <td>{{ $vehicle->placa ?? 'N/A' }}</td>
                    <td>{{ $ride->lugar_salida ?? 'N/A' }}</td>
                    <td>{{ $ride->lugar_llegada ?? 'N/A' }}</td>
                    <td>{{ $ride->fecha_hora ?? 'N/A' }}</td>
                    <td>{{ $reservation->estado }}</td>
                    <td>
                        @if ($ride && $ride->chofer)
                        {{ $ride->chofer->nombre }} {{ $ride->chofer->apellido }}
                        @else
                            N/A
                        @endif
                    </td>

                    <td>{{ $reservation->fecha_reserva }}</td>
                    <td>
                        @if ($reservation->estado === 'PENDIENTE' || $reservation->estado === 'ACEPTADA')
                            <form method="POST" action="{{ route('reservations.cancel', $reservation) }}"
                                  onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva?');">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    Cancelar
                                </button>
                            </form>
                        @else
                            <button class="btn btn-disabled" disabled>No disponible</button>
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
