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

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="text-primary fw-bold mb-0"><i class="bi bi-archive me-2"></i>Nuevo Insumo</h3>
                            <a href="{{ route('insumo.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-1"></i> Volver a la lista
                            </a>
                        </div>
                        <form method="POST" action="#">
                            @csrf
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_id" class="form-label">Tipo de Insumo</label>
                                <select class="form-select" id="tipo_id" name="tipo_id" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Unidad de medida y estado eliminados. Estado será 1 (Activo) por defecto. -->
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('insumo.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Insumo</button>
                            </div>
                        </form>
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
