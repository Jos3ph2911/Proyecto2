<x-app-layout>

    
    <x-slot name="header">
        <div class="flex items-center justify-between">

            
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Panel del chofer
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Aquí podés ver y administrar todos tus rides como chofer.
                </p>
            </div>

            
            @php
                $user = Auth::user();
                // Foto del chofer en public/perfiles/
                $fotoPath = $user->foto ? asset('perfiles/' . $user->foto) : null;
            @endphp

            <div class="flex items-center">
                
                <div class="h-10 w-10 rounded-full overflow-hidden border border-gray-300 flex-shrink-0">
                    @if ($fotoPath)
                        
                        <img
    src="{{ $fotoPath }}"
    alt="Foto de {{ $user->name }}"
    style="
        width: 40px;
        height: 40px;
        border-radius: 9999px;
        object-fit: cover;
        display: block;
    "
>

                    @else
                        
                        <div class="h-full w-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                            {{ strtoupper(mb_substr($user->nombre, 0, 1) . mb_substr($user->apellido, 0, 1)) }}

                        </div>
                    @endif
                </div>

                
                <div class="ml-3 text-right">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $user->name }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $user->email }}
                    </div>
                </div>
            </div>
        </div>
    </x-slot>


    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

  
            @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-200 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl">

                
                <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Mis rides
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Administra tus rides publicados, revisa reservas y edita su información.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        

                        <a href="{{ route('vehicles.index') }}"
                           class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Mis vehículos
                        </a>

                        <a href="{{ route('rides.create') }}"
                           class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                            + Nuevo ride
                        </a>

                        
                    </div>

                </div>

                
                <div class="px-6 py-5">
                    @if ($rides->isEmpty())
                        <p class="text-sm text-gray-500">No tienes rides registrados.</p>
                    @else

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">

                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Placa</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origen</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destino</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y hora</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Esp. totales</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Esp. disp.</th>
                                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Costo x espacio</th>
                                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-100">

                                @foreach ($rides as $ride)
                                    <tr>

                                        
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            @if ($ride->vehicle && $ride->vehicle->foto)
                                                <img src="{{ asset('storage/' . $ride->vehicle->foto) }}"
                                                     alt="Vehículo"
                                                     class="h-14 w-20 object-cover rounded-md border border-gray-200">
                                            @else
                                                <span class="text-xs text-gray-400 italic">Sin foto</span>
                                            @endif
                                        </td>

                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ $ride->vehicle->placa ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ $ride->vehicle->marca ?? 'N/A' }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ $ride->vehicle->modelo ?? 'N/A' }}</td>

                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ $ride->titulo }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ $ride->lugar_salida }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ $ride->lugar_llegada }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ $ride->fecha_hora }}</td>

                                        <td class="px-3 py-2 text-center text-gray-900">{{ $ride->espacios_totales }}</td>
                                        <td class="px-3 py-2 text-center text-gray-900">{{ $ride->espacios_disponibles }}</td>

                                        <td class="px-3 py-2 text-right text-gray-900">
                                            {{ number_format($ride->costo_por_espacio, 2) }}
                                        </td>

                                       
                                        <td class="px-3 py-2 whitespace-nowrap">
    <div class="flex flex-col items-center gap-2">

        
        <form method="GET" action="{{ route('driver.reservations.index', $ride) }}">
            <button type="submit"
                style="
                    width: 120px;
                    height: 38px;
                    border-radius: 6px;
                    background: #2563eb; /* azul */
                    color: white;
                    font-size: 14px;
                    font-weight: 600;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border: none;
                ">
                Reservas
            </button>
        </form>

        
        <form method="GET" action="{{ route('rides.edit', $ride) }}">
            <button type="submit"
                style="
                    width: 120px;
                    height: 38px;
                    border-radius: 6px;
                    background: #f3f4f6;
                    color: #374151;
                    font-size: 14px;
                    font-weight: 600;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border: 1px solid #9ca3af;
                ">
                Editar
            </button>
        </form>

        
        <form action="{{ route('rides.destroy', $ride) }}" method="POST"
              onsubmit="return confirm('¿Seguro que deseas eliminar este ride?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                style="
                    width: 120px;
                    height: 38px;
                    border-radius: 6px;
                    background: #dc2626;
                    color: white;
                    font-size: 14px;
                    font-weight: 600;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border: none;
                ">
                Eliminar
            </button>
        </form>

    </div>
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
    </div>

</x-app-layout>
