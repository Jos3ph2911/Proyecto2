<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservas del ride - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 1100px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; font-size: 13px; }
        th { background: #f0f0f0; }
        .btn { padding: 6px 10px; border-radius: 4px; text-decoration: none; }
        .btn-accept { background: #16a34a; color: white; border: none; cursor: pointer; }
        .btn-reject { background: #dc2626; color: white; border: none; cursor: pointer; }
        .btn-cancel { background: #ef4444; color: white; border: none; cursor: pointer; }
        .btn-disabled { background: #9ca3af; color: white; cursor: not-allowed; border: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Reservas del ride: {{ $ride->titulo }}</h1>

    <p><strong>Vehículo:</strong> {{ $ride->vehicle->placa }}</p>
    <p><strong>Fecha/hora:</strong> {{ $ride->fecha_hora }}</p>
    <p><strong>Espacios disponibles:</strong> {{ $ride->espacios_disponibles }}</p>

    @if (session('status'))
        <div style="color: green; margin-bottom: 10px;">{{ session('status') }}</div>
    @endif

    @if ($reservations->isEmpty())
        <p>No hay reservas para este ride.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Pasajero</th>
                <th>Correo</th>
                <th>Fecha solicitud</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->pasajero->nombre }} {{ $reservation->pasajero->apellido }}</td>
                    <td>{{ $reservation->pasajero->email }}</td>
                    <td>{{ $reservation->fecha_reserva }}</td>
                    <td>{{ $reservation->estado }}</td>

                    <td>
                        @if ($reservation->estado === 'PENDIENTE')
                            {{-- ACEPTAR --}}
                            <form action="{{ route('driver.reservations.accept', $reservation) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-accept">Aceptar</button>
                            </form>

                            {{-- RECHAZAR --}}
                            <form action="{{ route('driver.reservations.reject', $reservation) }}"
                                  method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-reject">Rechazar</button>
                            </form>

                        @elseif ($reservation->estado === 'ACEPTADA')
                            {{-- CANCELAR (CHOFER) --}}
                            <form action="{{ route('driver.reservations.cancelByDriver', $reservation) }}"
                                  method="POST" style="display:inline;"
                                  onsubmit="return confirm('¿Seguro que deseas cancelar esta reserva aceptada?');">
                                @csrf
                                <button class="btn btn-cancel">Cancelar</button>
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
