<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear administrador - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 6px; margin-top: 4px; box-sizing: border-box; }
        .error { color: #dc2626; font-size: 13px; }
        .btn { padding: 6px 10px; border-radius: 4px; border:none; cursor:pointer; }
        .btn-primary { background: #1d4ed8; color: #fff; }
        .btn-link { text-decoration: none; color: #1d4ed8; margin-bottom: 10px; display:inline-block; }
    </style>
</head>
<body>
<div class="container">
    <h1>Crear nuevo administrador</h1>

    <a href="{{ route('admin.users.index') }}" class="btn-link">← Volver al listado</a>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}">
        @error('nombre')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido" value="{{ old('apellido') }}">
        @error('apellido')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="cedula">Cédula</label>
        <input type="text" name="cedula" id="cedula" value="{{ old('cedula') }}">
        @error('cedula')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="fecha_nacimiento">Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
        @error('fecha_nacimiento')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}">
        @error('telefono')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="email">Correo electrónico</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}">
        @error('email')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password">
        @error('password')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="password_confirmation">Confirmar contraseña</label>
        <input type="password" name="password_confirmation" id="password_confirmation">

        <div style="margin-top:15px;">
            <button type="submit" class="btn btn-primary">Crear administrador</button>
        </div>
    </form>
</div>
</body>
</html>
