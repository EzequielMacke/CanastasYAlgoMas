@extends('layouts.app')

@section('titulo', 'Nueva venta')

@push('styles')
<style>
    main { max-width: 1380px !important; }

    .venta-grid {
        display: grid;
        grid-template-columns: 200px 1fr 1fr 300px;
        gap: 16px;
        align-items: start;
    }

    .panel {
        background: #fff;
        border: 1px solid #e8e0d8;
        border-radius: 10px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
        min-height: 520px;
        max-height: calc(100vh - 160px);
        overflow: hidden;
    }

    .panel-header {
        padding: 14px 16px 10px;
        border-bottom: 1px solid #ede7df;
        flex-shrink: 0;
    }

    .panel-title {
        font-size: 12px;
        font-weight: 600;
        color: #6b5744;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* ── Vendedor cards ── */
    .vendedor-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 12px;
        overflow-y: auto;
        flex: 1;
    }

    .vendedor-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border: 2px solid #e8e0d8;
        border-radius: 8px;
        cursor: pointer;
        background: #fdfaf7;
        transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
        user-select: none;
    }

    .vendedor-card:hover {
        border-color: #a08c78;
        background: #fff;
        box-shadow: 0 2px 6px rgba(160,140,120,0.12);
    }

    .vendedor-card.seleccionado {
        border-color: #2c2117;
        background: #f5f0eb;
        box-shadow: 0 2px 8px rgba(44,33,23,0.12);
    }

    .vendedor-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #2c2117;
        color: #f5f0eb;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        letter-spacing: 0.02em;
    }

    .vendedor-card.seleccionado .vendedor-avatar {
        background: #6b5744;
    }

    .vendedor-nombre {
        font-size: 13px;
        font-weight: 500;
        color: #2c2117;
        line-height: 1.3;
    }

    /* ── Catálogo ── */
    .catalogo-search {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d8cfc7;
        border-radius: 7px;
        background: #fdfaf7;
        font-size: 13px;
        font-family: inherit;
        color: #2c2117;
        outline: none;
    }

    .catalogo-search:focus {
        border-color: #a08c78;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(160,140,120,0.15);
    }

    .catalogo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 12px 16px 16px;
        overflow-y: auto;
        flex: 1;
    }

    .art-card {
        background: #fdfaf7;
        border: 1px solid #e8e0d8;
        border-radius: 8px;
        padding: 10px;
        cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .art-card:hover {
        border-color: #a08c78;
        background: #fff;
        box-shadow: 0 2px 8px rgba(160,140,120,0.15);
    }

    .art-card img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 6px;
        background: #f5f0eb;
    }

    .art-card-img-placeholder {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 6px;
        background: #f5f0eb;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #c4b8ac;
    }

    .art-card-nombre { font-size: 12px; font-weight: 600; color: #2c2117; line-height: 1.3; }
    .art-card-precio { font-size: 11px; font-weight: 600; color: #27794a; }
    .art-card-stock  { font-size: 11px; color: #a08c78; }
    .art-card-stock.sin-stock { color: #c0392b; }

    /* ── Carrito ── */
    .carrito-items {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        overflow-y: auto;
        padding: 12px 16px;
    }

    .carrito-item {
        background: #fdfaf7;
        border: 1px solid #e8e0d8;
        border-radius: 8px;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .carrito-item-nombre {
        font-size: 12px;
        font-weight: 600;
        color: #2c2117;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 6px;
    }

    .carrito-item-nombre button {
        background: none;
        border: none;
        cursor: pointer;
        color: #c4b8ac;
        padding: 0;
        flex-shrink: 0;
        transition: color 0.15s;
    }

    .carrito-item-nombre button:hover { color: #c0392b; }

    .carrito-item-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }

    .carrito-item-inputs label {
        font-size: 10px;
        font-weight: 500;
        color: #a08c78;
        margin-bottom: 3px;
        display: block;
    }

    .carrito-item-inputs input {
        width: 100%;
        padding: 5px 8px;
        border: 1px solid #d8cfc7;
        border-radius: 5px;
        background: #fff;
        font-size: 12px;
        font-family: inherit;
        color: #2c2117;
        outline: none;
    }

    .carrito-item-inputs input:focus {
        border-color: #a08c78;
        box-shadow: 0 0 0 2px rgba(160,140,120,0.12);
    }

    .carrito-item-inputs input[readonly] {
        background: #f5f0eb;
        color: #6b5744;
        cursor: default;
    }

    .carrito-item-subtotal {
        font-size: 11px;
        color: #6b5744;
        text-align: right;
        font-weight: 600;
    }

    .carrito-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #c4b8ac;
        font-size: 12px;
        padding: 40px 0;
        flex: 1;
    }

    /* ── Footer carrito ── */
    .carrito-footer {
        padding: 12px 16px;
        border-top: 1px solid #ede7df;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .cliente-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d8cfc7;
        border-radius: 7px;
        background: #fdfaf7;
        font-size: 13px;
        font-family: inherit;
        color: #2c2117;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .cliente-input:focus {
        border-color: #a08c78;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(160,140,120,0.15);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-label { font-size: 12px; font-weight: 600; color: #6b5744; text-transform: uppercase; letter-spacing: 0.05em; }
    .total-value { font-size: 18px; font-weight: 700; color: #2c2117; }
</style>
@endpush

@section('contenido')

<div class="section-header">
    <h1>Nueva venta</h1>
    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
        Volver
    </a>
</div>

@php
    $articulos_productos = $articulos->filter(fn($a) => !$a->esServicio());
    $articulos_servicios = $articulos->filter(fn($a) => $a->esServicio());
@endphp

<form method="POST" action="{{ route('ventas.store') }}" id="form-venta">
@csrf

<div class="venta-grid">

    {{-- ── PANEL 1: Vendedores ── --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Vendedor</div>
        </div>
        <div class="vendedor-list">
            @foreach($vendedores as $v)
                @php $iniciales = strtoupper(substr($v->nombre,0,1) . substr($v->apellido,0,1)); @endphp
                <div class="vendedor-card" data-id="{{ $v->id }}" onclick="seleccionarVendedor(this)">
                    <div class="vendedor-avatar">{{ $iniciales }}</div>
                    <div class="vendedor-nombre">{{ $v->nombre }}<br><span style="font-weight:400;color:#6b5744;">{{ $v->apellido }}</span></div>
                </div>
            @endforeach
            @if($vendedores->isEmpty())
                <div style="color:#c4b8ac;font-size:13px;text-align:center;padding:24px 0;">Sin vendedores.</div>
            @endif
        </div>
        <input type="hidden" name="vendedor_id" id="vendedor_id" value="{{ old('vendedor_id') }}">
        <div id="vendedor-error" style="display:none;padding:0 12px 10px;font-size:12px;color:#c0392b;"></div>
    </div>

    {{-- ── PANEL 2: Catálogo Artículos ── --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title" style="margin-bottom:10px;">Artículos</div>
            <input type="text" class="catalogo-search" id="search-productos" placeholder="Buscar artículo…">
        </div>
        <div class="catalogo-grid" id="grid-productos">
            @forelse($articulos_productos as $art)
                @php
                    $precio = $art->latestPrecioVenta?->precio ?? 0;
                    $stock  = $art->stock?->cantidad ?? 0;
                @endphp
                <div class="art-card"
                     data-id="{{ $art->id }}"
                     data-nombre="{{ $art->nombre }}"
                     data-precio="{{ $precio }}"
                     data-unidad="{{ $art->unidadMedida?->abreviatura ?? '' }}"
                     data-servicio="0"
                     onclick="agregarAlCarrito(this)">
                    @if($art->foto)
                        <img src="{{ asset('storage/' . $art->foto) }}" alt="{{ $art->nombre }}">
                    @else
                        <div class="art-card-img-placeholder">
                            <i data-lucide="package" style="width:24px;height:24px;"></i>
                        </div>
                    @endif
                    <div class="art-card-nombre">{{ $art->nombre }}</div>
                    @if($precio > 0)
                        <div class="art-card-precio">Gs. {{ number_format($precio, 0, ',', '.') }}</div>
                    @else
                        <div class="art-card-precio" style="color:#a08c78;">Sin precio</div>
                    @endif
                    <div class="art-card-stock {{ $stock <= 0 ? 'sin-stock' : '' }}">
                        Stock: {{ number_format($stock, 2, ',', '.') }} {{ $art->unidadMedida?->abreviatura ?? '' }}
                    </div>
                </div>
            @empty
                <div style="grid-column:1/-1;color:#c4b8ac;font-size:13px;text-align:center;padding:32px 0;">No hay artículos.</div>
            @endforelse
        </div>
    </div>

    {{-- ── PANEL 3: Catálogo Servicios ── --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title" style="margin-bottom:10px;">Servicios</div>
            <input type="text" class="catalogo-search" id="search-servicios" placeholder="Buscar servicio…">
        </div>
        <div class="catalogo-grid" id="grid-servicios">
            @forelse($articulos_servicios as $art)
                @php $costo = $art->precioCosto?->costo ?? 0; @endphp
                <div class="art-card"
                     data-id="{{ $art->id }}"
                     data-nombre="{{ $art->nombre }}"
                     data-precio="0"
                     data-unidad="{{ $art->unidadMedida?->abreviatura ?? '' }}"
                     data-servicio="1"
                     onclick="agregarAlCarrito(this)">
                    @if($art->foto)
                        <img src="{{ asset('storage/' . $art->foto) }}" alt="{{ $art->nombre }}">
                    @else
                        <div class="art-card-img-placeholder">
                            <i data-lucide="sparkles" style="width:24px;height:24px;"></i>
                        </div>
                    @endif
                    <div class="art-card-nombre">{{ $art->nombre }}</div>
                    @if($costo > 0)
                        <div class="art-card-stock">Ref: Gs. {{ number_format($costo, 0, ',', '.') }}</div>
                    @else
                        <div class="art-card-stock">Ingresar precio</div>
                    @endif
                </div>
            @empty
                <div style="grid-column:1/-1;color:#c4b8ac;font-size:13px;text-align:center;padding:32px 0;">No hay servicios.</div>
            @endforelse
        </div>
    </div>

    {{-- ── PANEL 4: Carrito / Resumen ── --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Resumen</div>
        </div>
        <div class="carrito-items" id="carrito-items">
            <div class="carrito-empty" id="carrito-empty">
                <i data-lucide="shopping-cart" style="width:28px;height:28px;"></i>
                <span>Sin ítems</span>
            </div>
        </div>
        <div class="carrito-footer">
            <div>
                <label style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;display:block;">
                    Cliente <span style="font-weight:400;">(opcional)</span>
                </label>
                <input type="text" name="cliente_nombre" class="cliente-input" value="{{ old('cliente_nombre') }}" placeholder="Nombre del cliente…" maxlength="150">
            </div>
            <div class="total-row">
                <span class="total-label">Total</span>
                <span class="total-value" id="total-display">Gs. 0</span>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;" id="btn-submit" disabled>
                <i data-lucide="check" style="width:15px;height:15px;"></i>
                Registrar venta
            </button>
            <div id="errores-form" style="display:none;font-size:12px;color:#c0392b;text-align:center;"></div>
        </div>
        <div id="hidden-inputs"></div>
    </div>

</div>
</form>

<script>
const carrito = new Map();

function formatGs(value) {
    return 'Gs. ' + Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// ── Vendedor ──
function seleccionarVendedor(card) {
    document.querySelectorAll('.vendedor-card').forEach(c => c.classList.remove('seleccionado'));
    card.classList.add('seleccionado');
    document.getElementById('vendedor_id').value = card.dataset.id;
    document.getElementById('vendedor-error').style.display = 'none';
    actualizarBoton();
}

// Restaurar selección si hay old()
(function() {
    const old = '{{ old('vendedor_id') }}';
    if (old) {
        const card = document.querySelector(`.vendedor-card[data-id="${old}"]`);
        if (card) card.classList.add('seleccionado');
    }
})();

// ── Carrito ──
function agregarAlCarrito(card) {
    const id       = card.dataset.id;
    const nombre   = card.dataset.nombre;
    const precio   = parseFloat(card.dataset.precio) || 0;
    const servicio = card.dataset.servicio === '1';
    const unidad   = card.dataset.unidad;

    if (carrito.has(id)) {
        const item = carrito.get(id);
        item.cantidad += 1;
        actualizarCantidadDOM(id, item.cantidad);
    } else {
        carrito.set(id, { nombre, precio, cantidad: 1, servicio, unidad });
        renderItem(id);
    }

    recalcularTotal();
    actualizarHiddenInputs();
    actualizarBoton();
}

function renderItem(id) {
    const item = carrito.get(id);
    document.getElementById('carrito-empty').style.display = 'none';

    const div = document.createElement('div');
    div.className = 'carrito-item';
    div.dataset.id = id;

    div.innerHTML = `
        <div class="carrito-item-nombre">
            <span>${item.nombre}</span>
            <button type="button" onclick="quitarDelCarrito('${id}')">
                <i data-lucide="x" style="width:13px;height:13px;"></i>
            </button>
        </div>
        <div class="carrito-item-inputs">
            <div>
                <label>Cant. ${item.unidad ? '(' + item.unidad + ')' : ''}</label>
                <input type="number" class="ci-cantidad" data-id="${id}" value="${item.cantidad}" min="0.0001" step="any">
            </div>
            <div>
                <label>Precio (Gs.)</label>
                <input type="number" class="ci-precio" data-id="${id}" value="${item.precio > 0 ? item.precio : ''}"
                       min="0" step="any" ${item.servicio ? '' : 'readonly'}
                       placeholder="${item.servicio ? 'Ingresar' : '—'}">
            </div>
        </div>
        <div class="carrito-item-subtotal" id="sub-${id}">${formatGs(item.cantidad * item.precio)}</div>
    `;

    document.getElementById('carrito-items').appendChild(div);
    lucide.createIcons();
}

function actualizarCantidadDOM(id, cantidad) {
    const input = document.querySelector(`.ci-cantidad[data-id="${id}"]`);
    if (input) { input.value = cantidad; actualizarSubtotal(id); }
}

function quitarDelCarrito(id) {
    carrito.delete(id);
    const el = document.querySelector(`.carrito-item[data-id="${id}"]`);
    if (el) el.remove();
    if (carrito.size === 0) document.getElementById('carrito-empty').style.display = '';
    recalcularTotal();
    actualizarHiddenInputs();
    actualizarBoton();
}

function actualizarSubtotal(id) {
    const item = carrito.get(id);
    if (!item) return;
    const el = document.getElementById(`sub-${id}`);
    if (el) el.textContent = formatGs(item.cantidad * item.precio);
}

function recalcularTotal() {
    let total = 0;
    carrito.forEach(i => { total += i.cantidad * i.precio; });
    document.getElementById('total-display').textContent = formatGs(total);
}

function actualizarHiddenInputs() {
    const wrap = document.getElementById('hidden-inputs');
    wrap.innerHTML = '';
    let i = 0;
    carrito.forEach((item, id) => {
        wrap.innerHTML += `
            <input type="hidden" name="items[${i}][articulo_id]" value="${id}">
            <input type="hidden" name="items[${i}][cantidad]" value="${item.cantidad}" id="h-cant-${id}">
            <input type="hidden" name="items[${i}][precio_unitario]" value="${item.precio}" id="h-precio-${id}">
        `;
        i++;
    });
}

function actualizarBoton() {
    const vendedorOk = !!document.getElementById('vendedor_id').value;
    let itemsOk = carrito.size > 0;
    carrito.forEach(item => { if (item.servicio && item.precio <= 0) itemsOk = false; });
    document.getElementById('btn-submit').disabled = !(vendedorOk && itemsOk);
}

// ── Delegación inputs carrito ──
document.getElementById('carrito-items').addEventListener('input', function(e) {
    const t  = e.target;
    const id = t.dataset.id;
    if (!id || !carrito.has(id)) return;
    const item = carrito.get(id);

    if (t.classList.contains('ci-cantidad')) {
        const v = parseFloat(t.value);
        item.cantidad = isNaN(v) || v <= 0 ? 0.001 : v;
        document.getElementById(`h-cant-${id}`).value = item.cantidad;
    } else if (t.classList.contains('ci-precio')) {
        const v = parseFloat(t.value);
        item.precio = isNaN(v) || v < 0 ? 0 : v;
        document.getElementById(`h-precio-${id}`).value = item.precio;
    }

    carrito.set(id, item);
    actualizarSubtotal(id);
    recalcularTotal();
    actualizarBoton();
});

// ── Búsqueda catálogos ──
document.getElementById('search-productos').addEventListener('input', function() {
    filtrar('grid-productos', this.value);
});
document.getElementById('search-servicios').addEventListener('input', function() {
    filtrar('grid-servicios', this.value);
});
function filtrar(gridId, q) {
    document.querySelectorAll(`#${gridId} .art-card`).forEach(c => {
        c.style.display = c.dataset.nombre.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
    });
}

// ── Submit ──
document.getElementById('form-venta').addEventListener('submit', function(e) {
    if (!document.getElementById('vendedor_id').value) {
        e.preventDefault();
        document.getElementById('vendedor-error').textContent = 'Seleccioná un vendedor.';
        document.getElementById('vendedor-error').style.display = 'block';
        return;
    }
    if (carrito.size === 0) {
        e.preventDefault();
        mostrarError('Agregá al menos un ítem.');
        return;
    }
    let err = null;
    carrito.forEach(item => {
        if (item.servicio && item.precio <= 0) err = `Ingresá el precio para "${item.nombre}".`;
    });
    if (err) { e.preventDefault(); mostrarError(err); }
});

function mostrarError(msg) {
    const el = document.getElementById('errores-form');
    el.textContent = msg;
    el.style.display = 'block';
    setTimeout(() => { el.style.display = 'none'; }, 4000);
}
</script>

@endsection
