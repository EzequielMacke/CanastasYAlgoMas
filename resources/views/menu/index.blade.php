<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú - @yield('title', 'Aplicación')</title>
    @include('partials.head')
</head>
<body>



@include('partials.menu')


<div class="container-fluid">
    <main class="p-4">
        @if(session('status'))
            <div class="alert alert-info small">{{ session('status') }}</div>
        @endif

        <div class="row row-cols-1 row-cols-md-5 g-4 justify-content-center align-items-center" style="min-height:60vh;">
            <!-- Tarjeta 1: Ingresos -->
            <div class="col">
                <a href="{{ route('ingreso.index') }}" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-journal-arrow-down modern-icon icon-ingresos"></i>
                            <div class="modern-title">Ingresos</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 2: Egresos -->
            <div class="col">
                <a href="#" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-box-arrow-left modern-icon icon-egresos"></i>
                            <div class="modern-title">Egresos</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 3: Inventario -->
            <div class="col">
                <a href="{{ route('inventario.index') }}" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-archive modern-icon icon-inventario"></i>
                            <div class="modern-title">Inventario</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 4: Usuarios -->
            <div class="col">
                <a href="#" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-people modern-icon icon-usuarios"></i>
                            <div class="modern-title">Usuarios</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 5: Reportes -->
            <div class="col">
                <a href="#" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-bar-chart-line modern-icon icon-reportes"></i>
                            <div class="modern-title">Reportes</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 6: Insumos -->
            <div class="col">
                <a href="{{ route('insumo.index') }}" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-boxes modern-icon icon-ingresos"></i>
                            <div class="modern-title">Insumos</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 7: Sucursales -->
            <div class="col">
                <a href="#" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-building modern-icon icon-sucursales"></i>
                            <div class="modern-title">Sucursales</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 8: Configuración -->
            <div class="col">
                <a href="#" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-gear modern-icon icon-configuracion"></i>
                            <div class="modern-title">Configuración</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 9: Permisos -->
            <div class="col">
                <a href="#" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-shield-lock modern-icon icon-permisos"></i>
                            <div class="modern-title">Permisos</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Tarjeta 10: Ayuda -->
            <div class="col">
                <a href="#" class="text-decoration-none">
                    <div class="card h-100 text-center modern-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-question-circle modern-icon icon-ayuda"></i>
                            <div class="modern-title">Ayuda</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
