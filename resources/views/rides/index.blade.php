<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis rides - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 1100px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; font-size: 13px; }
        th { background: #f0f0f0; }
        .btn { display: inline-block; padding: 6px 10px; font-size: 14px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background: #1d4ed8; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-link { color: #1d4ed8; text-decoration: underline; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .status { color: green; margin-bottom: 10px; }
        .vehiculo-img { width: 80px; height: 60px; object-fit: cover; border-radius: 4px; display: block; }
    </style>
</head>
<body>
<div class="container">
    <h1>Mis rides</h1>

    @if (session('status'))
        <div class="status">
            {{ session('status') }}
        </div>
    @endif

    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn-link">Volver al dashboard</a>

        <a href="{{ route('rides.create') }}" class="btn btn-primary">
            + Nuevo ride
        </a>
    </div>

    @if ($rides->isEmpty())
        <p>No tienes rides registrados.</p>
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
                <th>Esp. totales</th>
                <th>Esp. disp.</th>
                <th>Costo x espacio</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rides as $ride)
                <tr>
                    <!-- FOTO SOLA -->
                    <td>
                        @if ($ride->vehicle && $ride->vehicle->foto)
                            <img src="{{ asset('storage/' . $ride->vehicle->foto) }}"
                                 alt="Vehículo"
                                 class="vehiculo-img">
                        @else
                            <span style="font-size:12px; color:#777;">Sin foto</span>
                        @endif
                    </td>

                    <!-- PLACA -->
                    <td>
                        {{ $ride->vehicle->placa ?? 'N/A' }}
                    </td>

                    <!-- MARCA -->
                    <td>
                        {{ $ride->vehicle->marca ?? 'N/A' }}
                    </td>

                    <!-- MODELO -->
                    <td>
                        {{ $ride->vehicle->modelo ?? 'N/A' }}
                    </td>

                    <td>{{ $ride->titulo }}</td>
                    <td>{{ $ride->lugar_salida }}</td>
                    <td>{{ $ride->lugar_llegada }}</td>
                    <td>{{ $ride->fecha_hora }}</td>
                    <td>{{ $ride->espacios_totales }}</td>
                    <td>{{ $ride->espacios_disponibles }}</td>
                    <td>{{ number_format($ride->costo_por_espacio, 2) }}</td>

                    <td>
                        <a href="{{ route('rides.edit', $ride) }}" class="btn-link">
                            Editar
                        </a>

                        <form action="{{ route('rides.destroy', $ride) }}" method="POST"
                              style="display:inline-block"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este ride?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="border:none; cursor:pointer;">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
</body>
</html>
