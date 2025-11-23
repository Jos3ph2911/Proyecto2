<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo ride - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="datetime-local"], select {
            width: 100%; padding: 6px; margin-top: 4px; box-sizing: border-box;
        }
        .btn { display: inline-block; padding: 6px 10px; font-size: 14px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background: #1d4ed8; color: #fff; border: none; cursor: pointer; }
        .btn-link { color: #1d4ed8; text-decoration: underline; }
        ul { margin-top: 5px; }
        li { font-size: 13px; color: #b91c1c; }
        .buttons { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; }
        small { font-size: 12px; color: #555; }
    </style>
</head>
<body>
<div class="container">
    <h1>Crear nuevo ride</h1>

    @if ($errors->any())
        <div>
            <strong>Se encontraron algunos errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <a href="{{ route('rides.index') }}" class="btn-link">Volver a mis rides</a>

    <form method="POST" action="{{ route('rides.store') }}">
        @csrf

        <label for="vehicle_id">Vehículo</label>
        <select id="vehicle_id" name="vehicle_id">
            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                    {{ $vehicle->placa }} (capacidad: {{ $vehicle->capacidad }})
                </option>
            @endforeach
        </select>

        <label for="titulo">Título del ride</label>
        <input id="titulo" name="titulo" type="text" value="{{ old('titulo') }}" required>

        <label for="lugar_salida">Lugar de salida</label>
        <input id="lugar_salida" name="lugar_salida" type="text" value="{{ old('lugar_salida') }}" required>

        <label for="lugar_llegada">Lugar de llegada</label>
        <input id="lugar_llegada" name="lugar_llegada" type="text" value="{{ old('lugar_llegada') }}" required>

        <label for="fecha_hora">Fecha y hora</label>
        <input id="fecha_hora" name="fecha_hora" type="datetime-local"
               value="{{ old('fecha_hora') }}" required>
        <small>Ejemplo: 2025-11-25T08:30</small>

        <label for="costo_por_espacio">Costo por espacio</label>
        <input id="costo_por_espacio" name="costo_por_espacio" type="number" step="0.01"
               value="{{ old('costo_por_espacio') }}" required>

        <label for="espacios_totales">Espacios totales</label>
        <input id="espacios_totales" name="espacios_totales" type="number" min="1"
               value="{{ old('espacios_totales') }}" required>
        <small>No puede ser mayor que la capacidad del vehículo.</small>

        <div class="buttons">
            <a href="{{ route('rides.index') }}" class="btn-link">Cancelar</a>

            <button type="submit" class="btn btn-primary">
                Guardar ride
            </button>
        </div>
    </form>
</div>
</body>
</html>
