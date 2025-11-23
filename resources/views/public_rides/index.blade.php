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
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; font-size: 13px; }
        th { background: #f0f0f0; }
        .filtros { display: flex; gap: 10px; margin-top: 10px; }
        .filtros input { padding: 4px; }
        .btn { display: inline-block; padding: 5px 9px; font-size: 13px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background: #1d4ed8; color: #fff; border: none; cursor: pointer; }
        .btn-disabled { background: #9ca3af; color: #fff; border: none; cursor: not-allowed; }
        .vehiculo-img { width: 80px; height: 60px; object-fit: cover; border-radius: 4px; display: block; }
        .status { color: green; margin-top: 10px; }
        .error { color: #b91c1c; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Rides disponibles</h1>

    <div class="filtros">
        <form method="GET" action="{{ route('public.rides.index') }}">
            <input type="text" name="salida" placeholder="Lugar de salida"
                   value="{{ request('salida') }}">
            <input type="text" name="llegada" placeholder="Lugar de llegada"
                   value="{{ request('llegada') }}">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
    </div>

    @if (session('status'))
        <div class="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <div>- {{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if ($rides->isEmpty())
        <p>No hay rides disponibles por el momento.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Foto</th>
                <th>Placa</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Fecha y hora</th>
                <th>Costo x espacio</th>
                <th>Espacios disp.</th>
                <th>Chofer</th>
                <th>Acción</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rides as $ride)
                @php
                    $vehicle = $ride->vehicle;
                    $maxPasajeros = $vehicle ? max(0, $vehicle->capacidad - 1) : 0;
                    $ocupados = $ride->reservations()
                        ->whereIn('estado', ['PENDIENTE', 'ACEPTADA'])
                        ->count();
                    $disponibles = max(0, min($ride->espacios_totales, $maxPasajeros) - $ocupados);
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
                    <td>{{ $ride->lugar_salida }}</td>
                    <td>{{ $ride->lugar_llegada }}</td>
                    <td>{{ $ride->fecha_hora }}</td>
                    <td>{{ number_format($ride->costo_por_espacio, 2) }}</td>
                    <td>{{ $disponibles }}</td>
                    <td>
                        @if ($ride->chofer)
                        {{ $ride->chofer->nombre }} {{ $ride->chofer->apellido }}
                        @else
                         N/A
                        @endif
                    </td>

                    <td>
                        @if (!$user)
                            <span style="font-size:12px;">Inicia sesión para reservar</span>
                        @elseif ($user->rol !== 'pasajero')
                            <span style="font-size:12px;">Solo pasajeros pueden reservar</span>
                        @elseif ($disponibles <= 0)
                            <button class="btn btn-disabled" disabled>Sin espacios</button>
                        @else
                            <form method="POST" action="{{ route('reservations.store', $ride) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    Reservar
                                </button>
                            </form>
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
