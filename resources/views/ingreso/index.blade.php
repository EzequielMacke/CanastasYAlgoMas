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
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 w-100" style="width:100% !important;">
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
                        <div class="mb-5">
                            <div class="rounded-search-group position-relative w-100" style="max-width: 100%; margin: 0;">
                                <div class="input-group input-group-lg w-100">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-4" id="search-addon" style="border-right:0;">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 border-end-0 rounded-0" placeholder="Buscar ingresos por cualquier campo..." aria-label="Buscar ingresos" aria-describedby="search-addon" autocomplete="off" style="border-radius:0;">
                                    <button class="input-group-text bg-white border-start-0 rounded-end-pill pe-4 dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border-left:0; cursor:pointer;">
                                        <i class="bi bi-funnel"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="filterDropdown">
                                        <li><a class="dropdown-item" href="#">Usuario</a></li>
                                        <li><a class="dropdown-item" href="#">Estado</a></li>
                                        <li><a class="dropdown-item" href="#">Depósito</a></li>
                                        <li><a class="dropdown-item" href="#">Sucursal</a></li>
                                        <li><a class="dropdown-item" href="#">Insumo</a></li>
                                        <li><a class="dropdown-item" href="#">Cantidad</a></li>
                                        <li><a class="dropdown-item" href="#">Precio</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#">Todos los campos</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center align-items-stretch w-100 mx-0">
                            <!-- Tarjeta para crear nuevo ingreso -->
                            <div class="col d-flex align-items-stretch">
                                <a href="{{ route('ingreso.create') }}" class="text-decoration-none w-100 h-100">
                                    <div class="card h-100 d-flex flex-column align-items-center justify-content-center border-0 shadow-sm rounded-4 bg-light card-add-ingreso vertical-card" style="min-height: 260px; cursor:pointer;">
                                        <div class="display-1 text-success mb-3"><i class="bi bi-plus-circle"></i></div>
                                        <div class="fw-bold text-success fs-4">Nuevo Ingreso</div>
                                    </div>
                                </a>
                            </div>
                            <!-- Tarjetas de ingresos existentes -->
                            @forelse($ingresos->reverse() as $ingreso)
                                <div class="col d-flex align-items-stretch">
                                    <a href="#" class="text-decoration-none w-100 h-100" title="Ver detalle">
                                        <div class="card h-100 border-0 shadow-sm rounded-4 card-ingreso vertical-card" style="min-height: 260px; cursor:pointer;">
                                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                                                <div class="mb-3">
                                                    <span class="display-5 text-primary"><i class="bi bi-journal-arrow-down"></i></span>
                                                </div>
                                                <div class="mb-2">
                                                    <span class="badge bg-primary">#{{ $ingreso->id }}</span>
                                                </div>
                                                <div class="fw-bold text-dark mb-1 fs-5">{{ $ingreso->usuario->nombre ?? '-' }}</div>
                                                <div class="text-muted small mb-1">{{ $ingreso->fecha_ingreso ?? '-' }}</div>
                                                <div class="text-muted small mb-1">Estado: {{ $ingreso->estado->descripcion ?? '-' }}</div>
                                                <div class="text-muted small">{{ $ingreso->observacion ? Str::limit($ingreso->observacion, 40) : '-' }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">No hay ingresos registrados.</div>
                                </div>
                            @endforelse
                        </div>
                            <style>
            .vertical-card {
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
