<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresos - @yield('title', 'Aplicación')</title>
    @include('partials.head')
</head>
<body>



@include('partials.menu')


<div class="container-fluid">
    <main class="p-4">
        @if(session('status'))
            <div class="alert alert-info small">{{ session('status') }}</div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="text-primary fw-bold mb-0"><i class="bi bi-journal-arrow-down me-2"></i>Lista de Ingresos</h3>
                            <div>
                                <a href="{{ route('ingreso.create') }}" class="btn btn-success me-2">
                                    <i class="bi bi-plus-lg me-1"></i> Nuevo Ingreso
                                </a>
                                <a href="{{ route('menu.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left me-1"></i> Volver al menú
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Proveedor</th>
                                        <th>Usuario</th>
                                        <th>Estado</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ingresos as $ingreso)
                                        <tr>
                                            <td>{{ $ingreso->id }}</td>
                                            <td>{{ $ingreso->fecha ?? '-' }}</td>
                                            <td>{{ $ingreso->proveedor->nombre ?? '-' }}</td>
                                            <td>{{ $ingreso->usuario->nombre ?? '-' }}</td>
                                            <td>{{ $ingreso->estado->descripcion ?? '-' }}</td>
                                            <td>{{ number_format($ingreso->total, 2) }}</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-info" title="Ver"><i class="bi bi-eye"></i></a>
                                                <a href="#" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No hay ingresos registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .modern-card {
                background: #fff;
                border: none;
                border-radius: 1.5rem;
                box-shadow: 0 2px 16px 0 #0d6efd10;
                transition: box-shadow 0.2s, transform 0.2s;
                min-height: 180px;
            }
            .modern-card:hover {
                box-shadow: 0 4px 32px 0 #0d6efd22;
                transform: translateY(-4px) scale(1.03);
            }
            .modern-icon {
                font-size: 3.2rem;
                margin-bottom: 0.5rem;
            }
            .icon-ingresos { color: #0d6efd; }
            .icon-egresos { color: #f8b500; }
            .icon-inventario { color: #a259c6; }
            .icon-usuarios { color: #198754; }
            .icon-reportes { color: #ff4e50; }
            .icon-proveedores { color: #0dcaf0; }
            .icon-sucursales { color: #96e6a1; }
            .icon-configuracion { color: #fd7e14; }
            .icon-permisos { color: #f857a6; }
            .icon-ayuda { color: #185a9d; }
            .modern-title {
                font-size: 1.1rem;
                font-weight: 500;
                letter-spacing: 0.01em;
                color: #222;
                margin-top: 0.2rem;
                font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            }
        </style>
    </main>
</div>

</body>
</html>
