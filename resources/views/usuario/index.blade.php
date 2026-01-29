<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de usuarios</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Usuarios</h2>
            <div>
                <a href="{{ route('usuarios.create') }}" class="btn btn-success me-2"><i class="bi bi-plus-lg"></i> Agregar usuario</a>
                <a href="{{ route('menu.index') }}" class="btn btn-secondary">Volver al menú</a>
            </div>
        </div>

        <form class="mb-3" method="GET" action="#">
            <div class="input-group">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar usuario...">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
            </div>
        </form>

        <div class="card">
            <ul class="list-group list-group-flush">
                @forelse($usuarios as $usuario)
                    @php
                        $estado = $estados->firstWhere('id', $usuario->id_estado);
                        $desc = $estado ? $estado->descripcion : 'Desconocido';
                        $color = match(strtolower($desc)) {
                            'activo' => 'success',
                            'inactivo' => 'danger',
                            'pendiente' => 'warning',
                            default => 'secondary',
                        };
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            @if(strtolower($desc) === 'pendiente')
                                {{ $usuario->correo }}
                            @else
                                {{ $usuario->nombre }} {{ $usuario->apellido }}
                            @endif
                        </span>
                        <span class="badge bg-{{ $color }}">{{ $desc }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-center">No hay usuarios registrados.</li>
                @endforelse
            </ul>
        </div>
    </div>
</body>
</html>
