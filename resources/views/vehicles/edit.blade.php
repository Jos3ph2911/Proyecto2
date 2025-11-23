<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar vehículo - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%; padding: 6px; margin-top: 4px; box-sizing: border-box;
        }
        .btn { display: inline-block; padding: 6px 10px; font-size: 14px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background: #1d4ed8; color: #fff; border: none; cursor: pointer; }
        .btn-link { color: #1d4ed8; text-decoration: underline; }
        ul { margin-top: 5px; }
        li { font-size: 13px; color: #b91c1c; }
        .buttons { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; }
        img { margin-top: 5px; max-height: 80px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Editar vehículo</h1>

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
        <a href="{{ route('vehicles.index') }}" class="btn-link">Volver a mis vehículos</a>

    <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="placa">Placa</label>
        <input id="placa" name="placa" type="text" value="{{ old('placa', $vehicle->placa) }}" required>

        <label for="marca">Marca</label>
        <input id="marca" name="marca" type="text" value="{{ old('marca', $vehicle->marca) }}">

        <label for="modelo">Modelo</label>
        <input id="modelo" name="modelo" type="text" value="{{ old('modelo', $vehicle->modelo) }}">

        <label for="color">Color</label>
        <input id="color" name="color" type="text" value="{{ old('color', $vehicle->color) }}">

        <label for="anio">Año</label>
        <input id="anio" name="anio" type="number" value="{{ old('anio', $vehicle->anio) }}">

        <label for="capacidad">Capacidad (asientos)</label>
        <input id="capacidad" name="capacidad" type="number" min="1"
               value="{{ old('capacidad', $vehicle->capacidad) }}" required>

        <label>Foto actual</label>
        @if ($vehicle->foto)
            <img src="{{ asset('storage/' . $vehicle->foto) }}" alt="Foto vehículo">
        @else
            <p>No hay foto registrada.</p>
        @endif

        <label for="foto">Cambiar fotografía (opcional)</label>
        <input id="foto" name="foto" type="file">

        <div class="buttons">
            <a href="{{ route('vehicles.index') }}" class="btn-link">Cancelar</a>

            <button type="submit" class="btn btn-primary">
                Guardar cambios
            </button>
        </div>
    </form>
</div>
</body>
</html>
