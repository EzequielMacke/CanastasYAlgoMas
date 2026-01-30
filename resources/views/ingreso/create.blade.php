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
            <div class="col-12 col-lg-10 mx-auto">
                <form method="POST" action="#" class="p-0">
                    @csrf
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Ingreso</button>
                    </div>
                    <h3 class="mb-4 text-primary fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Nuevo Ingreso</h3>
                    <!-- CABECERA DEL INGRESO -->
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control-plaintext fw-semibold" value="{{ auth()->user()->name ?? '' }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Depósito</label>
                            <input type="text" class="form-control-plaintext fw-semibold" value="{{ session('sucursal_nombre', 'No definido') }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control-plaintext fw-semibold" value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea class="form-control" id="observacion" name="observacion" rows="2" placeholder="Observaciones del ingreso..."></textarea>
                    </div>
                    <!-- DETALLE DEL INGRESO -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0 fw-bold text-primary">Detalle de ingreso</h5>
                            <button type="button" class="btn btn-success fw-bold" id="agregar-fila" style="min-width:170px;">
                                <i class="bi bi-plus-circle me-1"></i>Agregar Insumo
                            </button>
                        </div>
                        <div id="insumos-cards-list" class="row g-3">
                            <!-- Las tarjetas de insumos se insertan aquí -->
                        </div>
                    </div>
                </form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Autocompletado personalizado para insumo ---
    const insumos = [
        @foreach($insumos as $insumo)
            "{{ addslashes($insumo->descripcion) }}",
        @endforeach
    ];
    let cardIndex = 0;
    const cardList = document.getElementById('insumos-cards-list');
    const agregarBtn = document.getElementById('agregar-fila');

    function crearCard(index) {
        // Crear la tarjeta de insumo
        const col = document.createElement('div');
        col.className = 'col-12 col-md-6 col-lg-4 insumo-card-col';
        col.innerHTML = `
            <div class="insumo-card shadow-sm p-4 mb-2 position-relative">
                <button type="button" class="btn-close btn-close-insumo position-absolute top-0 end-0 mt-2 me-2" title="Eliminar"></button>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Insumo</label>
                    <div class="position-relative insumo-autocomplete-group">
                        <input type="text" class="form-control insumo-autocomplete-solid" name="insumos[${index}][insumo_nombre]" autocomplete="off" required placeholder="Buscar o crear insumo">
                        <div class="insumo-dropdown-list" style="display:none;"></div>
                    </div>
                    <div class="insumo-alert mt-1 small text-warning" style="min-height:18px; display:block; visibility:hidden;"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Unidad</label>
                    <select class="form-select" name="insumos[${index}][unidad_medida_id]" required>
                        <option value="">Seleccione</option>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}">{{ $unidad->descripcion }} ({{ $unidad->abreviacion }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Cantidad</label>
                    <input type="number" step="any" min="0" class="form-control" name="insumos[${index}][cantidad]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Precio</label>
                    <input type="number" step="any" min="0" class="form-control" name="insumos[${index}][precio]" required>
                </div>
            </div>
        `;
        // Eliminar tarjeta
        col.querySelector('.btn-close-insumo').addEventListener('click', function() {
            col.remove();
        });
        // Autocompletado
        setupAutocomplete(col.querySelector('.insumo-autocomplete-group'), col.querySelector('.insumo-alert'));
        return col;
    }

    function setupAutocomplete(group, alertMsg) {
        const input = group.querySelector('.insumo-autocomplete-solid');
        const dropdown = group.querySelector('.insumo-dropdown-list');
        let insumoCreado = false;
        function positionDropdown() {
            const rect = input.getBoundingClientRect();
            dropdown.style.position = 'fixed';
            dropdown.style.top = (window.scrollY + rect.bottom) + 'px';
            dropdown.style.left = (window.scrollX + rect.left) + 'px';
            dropdown.style.width = rect.width + 'px';
        }
        input.addEventListener('focus', function() { mostrarLista(); positionDropdown(); });
        input.addEventListener('input', function() { mostrarLista(); positionDropdown(); });
        window.addEventListener('resize', positionDropdown);
        window.addEventListener('scroll', positionDropdown, true);
        input.addEventListener('blur', function() {
            setTimeout(() => dropdown.style.display = 'none', 200);
            setTimeout(() => mostrarAlerta(), 210);
        });
        function mostrarLista() {
            const val = input.value.trim();
            dropdown.innerHTML = '';
            // Mostrar todos los insumos si el input está vacío, si no filtrar
            let matches = val ? insumos.filter(i => i.toLowerCase().includes(val.toLowerCase())) : insumos.slice();
            const existe = insumos.some(i => i.toLowerCase() === val.toLowerCase());
            dropdown.style.display = 'block';
            insumoCreado = false;

            // Botón como primera "columna" de la fila
            const row = document.createElement('div');
            row.style.display = 'flex';
            row.style.flexDirection = 'row';
            row.style.alignItems = 'center';
            row.style.borderBottom = '1px solid #e5e7eb';
            row.style.background = '#f5f7fa';
            row.style.padding = '0.5rem 1rem';
            const crearBtn = document.createElement('button');
            crearBtn.type = 'button';
            crearBtn.className = 'btn btn-success btn-sm fw-bold';
            crearBtn.textContent = 'Crear';
            crearBtn.style.opacity = (matches.length > 0) ? '0.5' : '1';
            crearBtn.disabled = false;
            crearBtn.onclick = () => {
                input.value = val;
                dropdown.style.display = 'none';
                if (!insumos.includes(val)) insumos.unshift(val);
                insumoCreado = true;
                mostrarAlerta();
            };
            row.appendChild(crearBtn);
            // Solo mostrar el mensaje si no hay coincidencias
            if (!matches.length && val) {
                const msg = document.createElement('span');
                msg.className = 'text-muted small ms-2';
                msg.textContent = `Se creará el insumo "${val}"`;
                row.appendChild(msg);
            }
            dropdown.appendChild(row);

            // Cada insumo como fila
            if (matches.length) {
                matches.forEach(m => {
                    const itemRow = document.createElement('div');
                    itemRow.style.display = 'flex';
                    itemRow.style.flexDirection = 'row';
                    itemRow.style.alignItems = 'center';
                    itemRow.style.borderBottom = '1px solid #e5e7eb';
                    itemRow.style.padding = '0.5rem 1rem';
                    const emptyCell = document.createElement('div');
                    emptyCell.style.width = '80px';
                    const insumoCell = document.createElement('div');
                    insumoCell.textContent = m;
                    itemRow.appendChild(emptyCell);
                    itemRow.appendChild(insumoCell);
                    itemRow.className = 'insumo-dropdown-item';
                    itemRow.onclick = () => {
                        input.value = m;
                        dropdown.style.display = 'none';
                        insumoCreado = true;
                        mostrarAlerta();
                    };
                    dropdown.appendChild(itemRow);
                });
            }
            mostrarAlerta();
        }
        function mostrarAlerta() {
            const val = input.value.trim();
            const existe = insumos.some(i => i.toLowerCase() === val.toLowerCase());
            if (val && !existe && !insumoCreado) {
                alertMsg.textContent = `El insumo "${val}" todavía no fue creado.`;
                alertMsg.style.visibility = 'visible';
            } else {
                alertMsg.textContent = '';
                alertMsg.style.visibility = 'hidden';
            }
        }
    }

    // Inicializar con una tarjeta por defecto
    function agregarCard() {
        const card = crearCard(cardIndex);
        cardList.appendChild(card);
        cardIndex++;
    }
    agregarBtn.addEventListener('click', agregarCard);
    // Al cargar, una tarjeta por defecto
    agregarCard();
});
</script>
<style>
.insumo-card-col {
    display: flex;
}
.insumo-card {
    background: #fff;
    border-radius: 1.2rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 16px 0 #0d6efd10;
    margin-bottom: 0.5rem;
    width: 100%;
    position: relative;
    transition: box-shadow 0.2s, transform 0.2s;
}
.insumo-card:hover {
    box-shadow: 0 4px 32px 0 #0d6efd22;
    /* transform: translateY(-2px) scale(1.01); */
    transform: none;
}
.insumo-card .btn-close-insumo {
    z-index: 2;
}
.insumo-autocomplete-solid {
    background: #f5f7fa;
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
    min-height: 42px;
    font-weight: 500;
}
.insumo-autocomplete-solid:focus {
    border-color: #198754;
    box-shadow: 0 0 0 2px #19875433;
}
.insumo-dropdown-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 10;
    background: #fff;
    border-radius: 0.5rem;
    box-shadow: 0 2px 16px 0 #0d6efd10;
    border: 1px solid #e5e7eb;
    margin-top: 2px;
    padding: 0;
    max-height: 220px;
    overflow-y: auto;
    display: block;
}
.insumo-crear-box {
    background: #f5f7fa;
    border-radius: 0.4rem;
    margin: 0;
    border-bottom: 1px solid #e5e7eb;
    position: static;
    z-index: 1;
    display: flex;
    flex-direction: row;
    align-items: center;
}
.insumo-dropdown-item {
    padding: 0.5rem 1rem;
    cursor: pointer;
    border-radius: 0.3rem;
    transition: background 0.15s;
    display: flex;
    flex-direction: row;
    align-items: center;
}
.insumo-dropdown-item:hover {
    background: #f0f4f8;
}
.btn-success, .btn-success:disabled {
    background: #198754 !important;
    border: none;
    color: #fff;
    font-weight: 600;
    opacity: 1;
}
.btn-success:disabled, .btn-success[disabled] {
    opacity: 0.5 !important;
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
