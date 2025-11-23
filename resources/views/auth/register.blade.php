<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Aventones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Si tienes Vite/Tailwind activado, puedes dejar esto, si no, no pasa nada --}}
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="w-full max-w-md bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h1 class="text-2xl font-bold mb-4 text-center">Registro - Aventones</h1>

            {{-- Mensajes de estado --}}
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="mb-4">
                    <div class="font-medium text-red-600">
                        {{ __('Ups, ocurrió un problema con tus datos.') }}
                    </div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- NOMBRE --}}
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input id="nombre" name="nombre" type="text"
                           value="{{ old('nombre') }}"
                           required autofocus
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- APELLIDO --}}
                <div class="mt-4">
                    <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido</label>
                    <input id="apellido" name="apellido" type="text"
                           value="{{ old('apellido') }}"
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- CÉDULA --}}
                <div class="mt-4">
                    <label for="cedula" class="block text-sm font-medium text-gray-700">Cédula</label>
                    <input id="cedula" name="cedula" type="text"
                           value="{{ old('cedula') }}"
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- FECHA DE NACIMIENTO --}}
                <div class="mt-4">
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                    <input id="fecha_nacimiento" name="fecha_nacimiento" type="date"
                           value="{{ old('fecha_nacimiento') }}"
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- TELÉFONO --}}
                <div class="mt-4">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input id="telefono" name="telefono" type="text"
                           value="{{ old('telefono') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- ROL --}}
                <div class="mt-4">
                    <label for="rol" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select id="rol" name="rol"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">-- Selecciona un rol --</option>
                        <option value="chofer" {{ old('rol') == 'chofer' ? 'selected' : '' }}>Chofer</option>
                        <option value="pasajero" {{ old('rol') == 'pasajero' ? 'selected' : '' }}>Pasajero</option>
                    </select>
                </div>

                {{-- EMAIL --}}
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email') }}"
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- PASSWORD --}}
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input id="password" name="password" type="password"
                           required autocomplete="new-password"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                        ¿Ya tienes cuenta?
                    </a>

                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Registrarse
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
