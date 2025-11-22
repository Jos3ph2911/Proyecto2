<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <h1 class="text-2xl font-bold">Aventones</h1>
            </a>
        </x-slot>

        <!-- Mensajes de estado -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- Errores de validación -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- NOMBRE --}}
            <div>
                <x-input-label for="nombre" :value="__('Nombre')" />
                <x-text-input id="nombre" class="block mt-1 w-full"
                              type="text" name="nombre"
                              :value="old('nombre')" required autofocus />
            </div>

            {{-- APELLIDO --}}
            <div class="mt-4">
                <x-input-label for="apellido" :value="__('Apellido')" />
                <x-text-input id="apellido" class="block mt-1 w-full"
                              type="text" name="apellido"
                              :value="old('apellido')" required />
            </div>

            {{-- CÉDULA --}}
            <div class="mt-4">
                <x-input-label for="cedula" :value="__('Cédula')" />
                <x-text-input id="cedula" class="block mt-1 w-full"
                              type="text" name="cedula"
                              :value="old('cedula')" required />
            </div>

            {{-- FECHA DE NACIMIENTO --}}
            <div class="mt-4">
                <x-input-label for="fecha_nacimiento" :value="__('Fecha de nacimiento')" />
                <x-text-input id="fecha_nacimiento" class="block mt-1 w-full"
                              type="date" name="fecha_nacimiento"
                              :value="old('fecha_nacimiento')" required />
            </div>

            {{-- TELÉFONO --}}
            <div class="mt-4">
                <x-input-label for="telefono" :value="__('Teléfono')" />
                <x-text-input id="telefono" class="block mt-1 w-full"
                              type="text" name="telefono"
                              :value="old('telefono')" />
            </div>

            {{-- ROL --}}
            <div class="mt-4">
                <x-input-label for="rol" :value="__('Rol')" />
                <select id="rol" name="rol" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">-- Selecciona un rol --</option>
                    <option value="chofer" {{ old('rol') == 'chofer' ? 'selected' : '' }}>Chofer</option>
                    <option value="pasajero" {{ old('rol') == 'pasajero' ? 'selected' : '' }}>Pasajero</option>
                </select>
            </div>

            {{-- EMAIL --}}
            <div class="mt-4">
                <x-input-label for="email" :value="__('Correo electrónico')" />
                <x-text-input id="email" class="block mt-1 w-full"
                              type="email" name="email"
                              :value="old('email')" required />
            </div>

            {{-- PASSWORD --}}
            <div class="mt-4">
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full"
                              type="password"
                              name="password"
                              required autocomplete="new-password" />
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                              type="password"
                              name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('¿Ya tienes cuenta?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Registrarse') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
