<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventario - @yield('title', 'Aplicación')</title>
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
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 w-100" style="width:100% !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="text-primary fw-bold mb-0"><i class="bi bi-box-seam me-2"></i>Inventario Actual</h3>
                            <div>
                                <a href="{{ route('menu.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left me-1"></i> Volver al menú
                                </a>
                            </div>
                        </div>
                        <div class="mb-5">
                            <div class="rounded-search-group position-relative w-100" style="max-width: 100%; margin: 0;">
                                <div class="input-group input-group-lg w-100">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-4" id="search-addon" style="border-right:0;">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 border-end-0 rounded-0" placeholder="Buscar inventario por insumo, unidad, ingreso..." aria-label="Buscar inventario" aria-describedby="search-addon" autocomplete="off" style="border-radius:0;">
                                    <button class="input-group-text bg-white border-start-0 rounded-end-pill pe-4 dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border-left:0; cursor:pointer;">
                                        <i class="bi bi-funnel"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="filterDropdown">
                                        <li><a class="dropdown-item" href="#">Insumo</a></li>
                                        <li><a class="dropdown-item" href="#">Unidad de Medida</a></li>
                                        <li><a class="dropdown-item" href="#">Ingreso</a></li>
                                        <li><a class="dropdown-item" href="#">Cantidad</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#">Todos los campos</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless align-middle minimal-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Insumo</th>
                                        <th>Cantidad</th>
                                        <th>Unidad</th>
                                        <th>Ingreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventarios as $inventario)
                                        <tr>
                                            <td class="text-primary fw-bold">{{ $inventario->id }}</td>
                                            <td>{{ $inventario->insumo->descripcion ?? '-' }}</td>
                                            <td class="fw-semibold text-success">{{ $inventario->cantidad }}</td>
                                            <td><span class="badge bg-info text-dark">{{ $inventario->unidadMedida->descripcion ?? '-' }}</span></td>
                                            <td>#{{ $inventario->ingreso->id ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No hay inventario registrado.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                            <style>
            .minimal-table th {
                font-weight: 500;
                color: #222;
                background: #f8fafc;
                border-bottom: 1.5px solid #e0e3e7;
            }
            .minimal-table td {
                background: #fff;
                border-bottom: 1px solid #f0f4f8;
                font-size: 1.05rem;
                vertical-align: middle;
            }
            .minimal-table tr:hover td {
                background: #f6faff;
            }
            .rounded-search-group .input-group {
                border-radius: 2.5rem;
                overflow: hidden;
                box-shadow: 0 4px 24px 0 #0d6efd10, 0 1.5px 8px 0 #0d6efd08;
                background: #fff;
            }
            .rounded-search-group .form-control {
                background: #f8fafc;
                border: none;
                font-size: 1.18rem;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
                box-shadow: none;
            }
            .rounded-search-group .form-control:focus {
                background: #fff;
                box-shadow: none;
            }
            .rounded-search-group .input-group-text {
                background: #fff;
                border: none;
                font-size: 1.3rem;
                box-shadow: none;
                transition: background 0.18s;
            }
            .rounded-search-group .input-group-text:active,
            .rounded-search-group .input-group-text:focus {
                background: #f0f4f8;
            }
            .rounded-search-group .dropdown-menu {
                border-radius: 1rem;
                min-width: 200px;
                font-size: 1rem;
            }
                min-width: 220px;
                max-width: 100%;
                width: 100%;
                margin: 0 auto;
                border-radius: 1.5rem;
                box-shadow: 0 2px 16px 0 #0d6efd10;
                transition: box-shadow 0.2s, transform 0.2s;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .card-add-ingreso:hover {
                background: #e6f9ed !important;
                box-shadow: 0 4px 32px 0 #19875422;
                transform: translateY(-2px) scale(1.03);
            }
            .card-ingreso:hover {
                background: #f0f4f8 !important;
                box-shadow: 0 4px 32px 0 #0d6efd22;
                transform: translateY(-2px) scale(1.03);
            }
                            </style>
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
