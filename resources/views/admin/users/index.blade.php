<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de usuarios - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 1100px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        h2 { margin-top: 20px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; font-size: 13px; text-align: left; }
        th { background: #f0f0f0; }

        .status-msg { margin-top: 10px; color: green; }

        .badge { padding: 2px 6px; border-radius: 4px; font-size: 11px; }
        .badge-admin { background: #1d4ed8; color: #fff; }
        .badge-chofer { background: #16a34a; color: #fff; }
        .badge-pasajero { background: #6b7280; color: #fff; }
        .badge-super { background: #f59e0b; color: #000; margin-left: 4px; }

        .btn { padding: 4px 8px; border-radius: 4px; font-size: 12px; text-decoration: none; border:none; cursor:pointer; }
        .btn-delete { background: #dc2626; color: #fff; }
        .btn-state { background: #1d4ed8; color: #fff; }
        .btn-logout { background: #6b7280; color:#fff; }
        select { font-size: 12px; padding: 2px 4px; }

        .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }

        /* Tarjetas de resumen */
        .stats-grid { display:flex; flex-wrap:wrap; gap:10px; margin:10px 0 20px 0; }
        .stat-card { background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:8px 10px; font-size:12px; min-width:130px; }
        .stat-label { color:#4b5563; font-size:11px; }
        .stat-value { font-weight:bold; font-size:14px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Administración de usuarios</h1>

    <div class="top-bar">
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-state">Volver al dashboard</a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-state" style="margin-left:8px;">
                + Nuevo administrador
            </a>
        </div>

        {{-- Logout solo aquí, panel principal de admin --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-logout">Cerrar sesión</button>
        </form>
    </div>

    {{-- MENSAJE DE ESTADO --}}
    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

    {{-- RESUMEN GENERAL --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Usuarios totales</div>
            <div class="stat-value">{{ $totalUsers }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Administradores</div>
            <div class="stat-value">{{ $totalAdmins }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Choferes</div>
            <div class="stat-value">{{ $totalChoferes }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pasajeros</div>
            <div class="stat-value">{{ $totalPasajeros }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Usuarios ACTIVO</div>
            <div class="stat-value">{{ $totalActivos }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Usuarios INACTIVO</div>
            <div class="stat-value">{{ $totalInactivos }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Usuarios PENDIENTE</div>
            <div class="stat-value">{{ $totalPendientes }}</div>
        </div>
    </div>

    {{-- TABLA DE USUARIOS --}}
    <h2>Usuarios</h2>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>
                    {{ $user->nombre }} {{ $user->apellido }}

                    @if ($user->esSuperAdmin())
                        <span class="badge badge-super">SUPER ADMIN</span>
                    @endif
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->esAdministrador())
                        <span class="badge badge-admin">Admin</span>
                    @elseif($user->esChofer())
                        <span class="badge badge-chofer">Chofer</span>
                    @else
                        <span class="badge badge-pasajero">Pasajero</span>
                    @endif
                </td>
                <td>{{ $user->estado }}</td>
                <td>
                    {{-- Cambiar estado --}}
                    <form action="{{ route('admin.users.updateStatus', $user) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <select name="estado" onchange="this.form.submit()">
                            <option value="PENDIENTE" {{ $user->estado === 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                            <option value="ACTIVO"    {{ $user->estado === 'ACTIVO'    ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO"  {{ $user->estado === 'INACTIVO'  ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </form>

                    {{-- Eliminar usuario --}}
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                          style="display:inline-block; margin-left:4px;"
                          onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Eliminar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

    {{-- TABLA DE VIAJES (SOLO CHOFERES ACTIVO) --}}
    <h2>Viajes (choferes ACTIVO)</h2>

    @if ($rides->isEmpty())
        <p>No hay viajes registrados de choferes ACTIVO.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Fecha y hora</th>
                <th>Chofer</th>
                <th>Vehículo</th>
                <th>Esp. disp./totales</th>
                <th>Costo x espacio</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($rides as $ride)
                <tr>
                    <td>{{ $ride->id }}</td>
                    <td>{{ $ride->titulo }}</td>
                    <td>{{ $ride->lugar_salida }}</td>
                    <td>{{ $ride->lugar_llegada }}</td>
                    <td>{{ $ride->fecha_hora }}</td>
                    <td>
                        @if ($ride->chofer)
                            {{ $ride->chofer->nombre }} {{ $ride->chofer->apellido }}
                            ({{ $ride->chofer->email }})
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if ($ride->vehicle)
                            {{ $ride->vehicle->placa }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $ride->espacios_disponibles }} / {{ $ride->espacios_totales }}</td>
                    <td>{{ number_format($ride->costo_por_espacio, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

</div>
</body>
</html>
