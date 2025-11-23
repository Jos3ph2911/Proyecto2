<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - Aventones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex flex-col justify-center items-center">
    <div class="w-full max-w-md bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-2xl font-bold mb-4 text-center">Iniciar sesión - Aventones</h1>

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

        {{-- Botón VOLVER segun el origen --}}
        @php
            $from = request('from');
            if ($from === 'public') {
                // Volver a la página pública de rides
                $backUrl = route('public.rides.index');
            } elseif ($from === 'register') {
                // Volver al registro si viniste desde ahí
                $backUrl = route('register', ['from' => 'login']);
            } else {
                // Fallback: página pública
                $backUrl = route('public.rides.index');
            }
        @endphp

        <a href="{{ $backUrl }}"
           class="inline-block mb-4 text-sm text-blue-600 hover:underline">
            ← Volver
        </a>

        <form method="POST" action="{{ route('login', ['from' => $from]) }}">
            @csrf

            {{-- EMAIL --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Correo electrónico
                </label>
                <input id="email" name="email" type="email"
                       value="{{ old('email') }}"
                       required autofocus
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            {{-- PASSWORD --}}
            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Contraseña
                </label>
                <input id="password" name="password" type="password"
                       required autocomplete="current-password"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            {{-- RECORDAR --}}
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           name="remember">
                    <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6">
                {{-- Enlace a registro, indicando que vienes del login --}}
                <a href="{{ route('register', ['from' => 'login']) }}"
                   class="text-sm text-blue-600 hover:underline">
                    Crear cuenta nueva
                </a>

                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Iniciar sesión
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
