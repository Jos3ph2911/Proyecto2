<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel del pasajero
            </h2>

            <div class="flex items-center gap-3">
                @if($user->foto)
                    <img src="{{ asset('perfiles/' . $user->foto) }}"
                         style="width: 48px; height: 48px; object-fit: cover; border-radius: 9999px;">
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(mb_substr($user->nombre, 0, 1)) }}{{ strtoupper(mb_substr($user->apellido, 0, 1)) }}
                    </div>
                @endif

                <div class="text-sm">
                    <div class="font-semibold">
                        {{ $user->nombre }} {{ $user->apellido }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $user->email }}
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <p class="text-sm text-gray-700 mb-4">
                    Este es tu panel como pasajero.
                    Desde aquí podés buscar rides, revisar tus reservas y acceder a tu perfil.
                </p>

                <div class="flex flex-wrap justify-end gap-3">
                    <a href="{{ route('reservations.passenger.index') }}"
                       class="px-4 py-2 bg-gray-200 text-gray-800 text-xs rounded-md hover:bg-gray-300">
                        Mis reservas
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="px-4 py-2 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-500">
                        Mi perfil
                    </a>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">
                    Buscar rides disponibles
                </h3>

                <form method="GET" action="{{ route('public.rides.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex flex-col">
                        <label for="origen" class="text-xs text-gray-600 mb-1">Lugar de salida</label>
                        <input
                            id="origen"
                            name="origen"
                            type="text"
                            value="{{ request('origen') }}"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-gray-300"
                        >
                    </div>

                    <div class="flex flex-col">
                        <label for="destino" class="text-xs text-gray-600 mb-1">Lugar de llegada</label>
                        <input
                            id="destino"
                            name="destino"
                            type="text"
                            value="{{ request('destino') }}"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-gray-300"
                        >
                    </div>

                    <div class="flex gap-2">
    <button type="submit"
            style="padding: 0.5rem 1rem; background-color: #2563eb; color: #ffffff; font-size: 0.75rem; border-radius: 0.375rem; border: none; cursor: pointer;">
        Buscar
    </button>

    <a href="{{ route('passenger.dashboard') }}"
   style="
        padding: 0.5rem 1rem;
        background-color: #6b7280;
        color: #ffffff;
        font-size: 0.75rem;
        border-radius: 0.375rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-align: center;
        height: 100%;
   ">
    Limpiar
</a>

</div>

                </form>

                <p class="mt-4 text-xs text-gray-500">
                    Al buscar, se abrirá la página de <strong>Rides disponibles</strong> con los resultados filtrados.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>
