<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de usuarios - Aventones</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; }
        .container { max-width: 1100px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; }
        h1 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
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
    </style>
</head>
<body>
<div class="container">
    <h1>Administración de usuarios</h1>

    <div class="top-bar">
        <div>
            <a href="{{ route('dashboard') }}" class="btn-state">Volver al dashboard</a>
            <a href="{{ route('admin.users.create') }}" class="btn-state" style="margin-left:8px;">
                + Nuevo administrador
            </a>
        </div>

        {{-- Logout solo en panel principal --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-logout">Cerrar sesión</button>
        </form>
    </div>

    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

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
                            <option value="ACTIVO"    {{ $user->estado === 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO"  {{ $user->estado === 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
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
</div>
</body>
</html>
