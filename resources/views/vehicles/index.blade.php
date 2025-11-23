<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis vehículos - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 900px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; font-size: 14px; }
        th { background: #f0f0f0; }
        .btn { display: inline-block; padding: 6px 10px; font-size: 14px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background: #1d4ed8; color: #fff; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-link { color: #1d4ed8; text-decoration: underline; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .status { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Mis vehículos</h1>

    @if (session('status'))
        <div class="status">
            {{ session('status') }}
        </div>
    @endif

    <div class="top-bar">
        <a href="{{ route('dashboard') }}" class="btn-link">Volver al dashboard</a>

        <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
            + Nuevo vehículo
        </a>
    </div>

    @if ($vehicles->isEmpty())
        <p>No tienes vehículos registrados.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>Foto</th>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Capacidad</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($vehicles as $vehicle)
                <tr>
                    <td>
                        @if ($vehicle->foto)
                            <img src="{{ asset('storage/' . $vehicle->foto) }}"
                                 alt="Foto vehículo"
                                 style="width:70px; height:50px; object-fit:cover; border-radius:4px;">
                        @else
                            <span style="font-size:12px; color:#777;">Sin foto</span>
                        @endif
                    </td>
                    <td>{{ $vehicle->placa }}</td>
                    <td>{{ $vehicle->marca }}</td>
                    <td>{{ $vehicle->modelo }}</td>
                    <td>{{ $vehicle->capacidad }}</td>
                    <td>
                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn-link">
                            Editar
                        </a>

                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" style="display:inline-block"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este vehículo?');">
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
