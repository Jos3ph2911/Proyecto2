<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Rides disponibles
            </h2>

            <div class="flex items-center gap-2">
                @if ($user && $user->esPasajero())
                    <a href="{{ route('reservations.passenger.index') }}"
                       class="px-4 py-2 bg-gray-200 text-gray-800 text-xs rounded-md hover:bg-gray-300">
                        Mis reservas
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-gray-600 text-white text-xs rounded-md hover:bg-gray-500">
                            Cerrar sesión
                        </button>
                    </form>

                @elseif ($user)
                    {{-- Admin / Chofer --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline-block">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-gray-600 text-white text-xs rounded-md hover:bg-gray-500">
                            Cerrar sesión
                        </button>
                    </form>

                @else
    {{-- Invitado --}}
    <a href="{{ route('login', ['from' => 'public']) }}"
       style="
            padding: 0.5rem 1rem;
            background-color: #4b5563;
            color: #ffffff;
            font-size: 0.75rem;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        ">
        Iniciar sesión
    </a>

                    <a href="{{ route('register', ['from' => 'public']) }}"
       style="
            padding: 0.5rem 1rem;
            background-color: #2563eb;
            color: #ffffff;
            font-size: 0.75rem;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        ">
        Registrarse
    </a>
@endif
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            {{-- FILTROS DE BÚSQUEDA --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">
                    Buscar rides disponibles
                </h3>

                <form method="GET" action="{{ route('public.rides.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex flex-col">
                        <label for="salida" class="text-xs text-gray-600 mb-1">Lugar de salida</label>
                        <input type="text" id="salida" name="salida" value="{{ request('salida') }}"
                               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-gray-300">
                    </div>

                    <div class="flex flex-col">
                        <label for="llegada" class="text-xs text-gray-600 mb-1">Lugar de llegada</label>
                        <input type="text" id="llegada" name="llegada" value="{{ request('llegada') }}"
                               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring focus:ring-gray-300">
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

                <p class="mt-3 text-xs text-gray-500">
                    Al buscar, se mostrarán los rides que coincidan con los filtros seleccionados.
                </p>

                @if (session('status'))
                    <div class="mt-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

            {{-- TABLA DE RIDES --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                @if ($rides->isEmpty())
                    <p class="text-sm text-gray-700">
                        No hay rides disponibles con los filtros seleccionados.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="px-3 py-2 text-left font-semibold">Foto</th>
                                <th class="px-3 py-2 text-left font-semibold">Placa</th>
                                <th class="px-3 py-2 text-left font-semibold">Marca</th>
                                <th class="px-3 py-2 text-left font-semibold">Modelo</th>
                                <th class="px-3 py-2 text-left font-semibold">Título</th>
                                <th class="px-3 py-2 text-left font-semibold">Origen</th>
                                <th class="px-3 py-2 text-left font-semibold">Destino</th>
                                <th class="px-3 py-2 text-left font-semibold">Fecha y hora</th>
                                <th class="px-3 py-2 text-left font-semibold">Costo x espacio</th>
                                <th class="px-3 py-2 text-left font-semibold">Esp. disp.</th>
                                <th class="px-3 py-2 text-left font-semibold">Chofer</th>
                                <th class="px-3 py-2 text-left font-semibold">Acción</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($rides as $ride)
                                @php
                                    $reservasDelPasajero = collect();
                                    $yaReservoEsteRide = false;

                                    if ($user && $user->esPasajero() && isset($reservationsByRide[$ride->id])) {
                                        $reservasDelPasajero = $reservationsByRide[$ride->id];
                                        $yaReservoEsteRide = $reservasDelPasajero->isNotEmpty();
                                    }
                                @endphp

                                <tr class="border-b">
                                    {{-- FOTO --}}
                                    <td class="px-3 py-2">
                                        @if ($ride->vehicle && $ride->vehicle->foto)
                                            <img src="{{ asset('storage/' . $ride->vehicle->foto) }}"
                                                 alt="Vehículo"
                                                 class="w-20 h-16 rounded-md object-cover">
                                        @else
                                            <span class="text-xs text-gray-500">Sin foto</span>
                                        @endif
                                    </td>

                                    {{-- VEHÍCULO --}}
                                    <td class="px-3 py-2">{{ $ride->vehicle->placa ?? 'N/A' }}</td>
                                    <td class="px-3 py-2">{{ $ride->vehicle->marca ?? 'N/A' }}</td>
                                    <td class="px-3 py-2">{{ $ride->vehicle->modelo ?? 'N/A' }}</td>

                                    {{-- RIDE --}}
                                    <td class="px-3 py-2">{{ $ride->titulo }}</td>
                                    <td class="px-3 py-2">{{ $ride->lugar_salida }}</td>
                                    <td class="px-3 py-2">{{ $ride->lugar_llegada }}</td>
                                    <td class="px-3 py-2">{{ $ride->fecha_hora }}</td>
                                    <td class="px-3 py-2">{{ number_format($ride->costo_por_espacio, 2) }}</td>
                                    <td class="px-3 py-2">{{ $ride->espacios_disponibles }}</td>

                                    {{-- CHOFER --}}
                                    <td class="px-3 py-2">
                                        @if ($ride->chofer)
                                            {{ $ride->chofer->nombre }} {{ $ride->chofer->apellido }}
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    {{-- ACCIÓN --}}
                                    <td class="px-3 py-2">
                                        @if (!$user)
                                            {{-- Invitado: botón "Reservar" → login --}}
                                            <a href="{{ route('login', ['from' => 'public']) }}"
                                                style="
                                                    padding: 4px 12px;
                                                    background: #2563eb;
                                                    color: white;
                                                    font-size: 12px;
                                                    border-radius: 6px;
                                                    text-decoration: none;
                                                    display: inline-block;
                                                ">
                                                    Reservar
                                            </a>


                                        @elseif ($user->esPasajero())
                                            @if ($ride->espacios_disponibles <= 0)
                                                <span style="
    display: inline-block;
    padding: 2px 6px;
    font-size: 11px;
    border-radius: 4px;
    background: #dc2626;
    color: white;
">
    Completo
</span>


                                            @elseif ($yaReservoEsteRide)
                                                <span style="
    display: inline-block;
    padding: 2px 6px;
    font-size: 11px;
    border-radius: 4px;
    background: #0ea5e9;
    color: white;
">
    Ya tienes una reserva en este ride
</span>


                                            @else
                                                <form action="{{ route('reservations.store', $ride) }}" method="POST">
                                                    @csrf
                                                    <form action="{{ route('reservations.store', $ride) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                        style="
                                                            padding: 4px 12px;
                                                            background: #2563eb;
                                                            color: white;
                                                            font-size: 12px;
                                                            border-radius: 6px;
                                                            border: none;
                                                            cursor: pointer;
                                                        ">
                                                    Reservar
                                                </button>
                                            </form>

                                                </form>
                                            @endif

                                        @else
                                            <span class="text-xs text-gray-500">
                                                Solo pasajeros pueden reservar
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
