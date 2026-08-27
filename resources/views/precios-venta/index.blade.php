@extends('layouts.app')

@section('titulo', 'Precios de Venta')

@section('contenido')

<style>
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:#fff; border-radius:12px; padding:32px 36px; width:100%; max-width:440px; box-shadow:0 8px 40px rgba(0,0,0,0.18); position:relative; }
    .modal-title { font-size:16px; font-weight:600; color:#1a1208; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .modal-close { position:absolute; top:16px; right:16px; background:none; border:none; cursor:pointer; color:#a08c78; padding:4px; border-radius:5px; display:flex; align-items:center; transition:color 0.15s,background 0.15s; }
    .modal-close:hover { color:#2c2117; background:#f5f0eb; }
    .precio-badge { display:inline-flex; align-items:center; gap:4px; border-radius:6px; padding:5px 10px; font-size:13px; font-weight:500; white-space:nowrap; }
    .badge-costo { background:#f5f0eb; color:#6b5744; border:1px solid #e8e0d8; }
    .badge-venta { background:#f0faf3; color:#27794a; border:1px solid #b8dfc6; }
    .sin-precio { color:#c4b8ac; font-size:12px; font-style:italic; }
    .search-wrap { display:flex; gap:12px; align-items:center; margin-bottom:20px; }
</style>

<div class="section-header">
    <div>
        <h1>Precios de venta</h1>
        <p style="font-size:13px;color:#a08c78;margin-top:4px;">Se toma el último precio cargado por artículo.</p>
    </div>
</div>

{{-- Buscador --}}
<form method="GET" action="{{ route('precios-venta.index') }}" class="search-wrap">
    <input type="hidden" name="filtro" value="{{ $filtro }}">
    <div class="input-wrap" style="max-width:360px;flex:1;">
        <i data-lucide="search" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
        <input type="text" name="buscar" value="{{ $buscar ?? '' }}" placeholder="Buscar artículo..." style="padding-left:38px;" autocomplete="off">
    </div>
    @if($buscar)
        <a href="{{ route('precios-venta.index', ['filtro' => $filtro]) }}" class="btn btn-secondary btn-sm">
            <i data-lucide="x" style="width:13px;height:13px;"></i>
            Limpiar
        </a>
    @endif
</form>

<div class="card" style="padding:0;overflow:hidden;">
    <div class="tabs">
        <a href="{{ route('precios-venta.index', array_filter(['filtro' => 'todos', 'buscar' => $buscar])) }}"
           class="tab-btn {{ $filtro === 'todos' ? 'activo' : '' }}">
            <i data-lucide="list" style="width:14px;height:14px;"></i>
            Todos
            <span class="tab-count">{{ $conPrecio->count() + $sinPrecio->count() }}</span>
        </a>
        <a href="{{ route('precios-venta.index', array_filter(['filtro' => 'con_precio', 'buscar' => $buscar])) }}"
           class="tab-btn {{ $filtro === 'con_precio' ? 'activo' : '' }}">
            <i data-lucide="tag" style="width:14px;height:14px;"></i>
            Con precio
            <span class="tab-count">{{ $conPrecio->count() }}</span>
        </a>
        <a href="{{ route('precios-venta.index', array_filter(['filtro' => 'sin_precio', 'buscar' => $buscar])) }}"
           class="tab-btn {{ $filtro === 'sin_precio' ? 'activo' : '' }}">
            <i data-lucide="ban" style="width:14px;height:14px;"></i>
            Sin precio
            <span class="tab-count">{{ $sinPrecio->count() }}</span>
        </a>
    </div>

    @if($articulos->isEmpty())
        <div class="empty">
            <i data-lucide="tag" style="width:28px;height:28px;"></i>
            <span>No se encontraron artículos.</span>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Artículo</th>
                    <th>Tipo</th>
                    <th style="text-align:right;">Precio de costo</th>
                    <th style="text-align:right;">Precio de venta</th>
                    <th style="text-align:right;color:#a08c78;">Margen</th>
                    <th style="width:130px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($articulos as $art)
                    @php
                        $costo  = $art->precioCosto?->costo ?? 0;
                        $venta  = $art->latestPrecioVenta?->precio ?? null;
                        $margen = ($costo > 0 && $venta !== null) ? (($venta - $costo) / $costo * 100) : null;
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                @if($art->foto)
                                    <img src="{{ Storage::url($art->foto) }}" style="width:32px;height:32px;border-radius:6px;object-fit:cover;border:1px solid #e8e0d8;">
                                @else
                                    <div style="width:32px;height:32px;border-radius:6px;background:#f5f0eb;border:1px solid #e8e0d8;display:flex;align-items:center;justify-content:center;">
                                        <i data-lucide="package" style="width:14px;height:14px;color:#c4b8ac;"></i>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:500;color:#1a1208;">{{ $art->nombre }}</div>
                                    <div style="font-size:11px;color:#a08c78;">{{ $art->unidadMedida->abreviatura }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:#6b5744;font-size:13px;">{{ $art->tipoArticulo->nombre }}</td>
                        <td style="text-align:right;">
                            @if($costo > 0)
                                <span class="precio-badge badge-costo">
                                    Gs. {{ number_format($costo, 0, ',', '.') }}
                                    <span style="font-size:11px;color:#a08c78;">/ {{ $art->unidadMedida->abreviatura }}</span>
                                </span>
                            @else
                                <span class="sin-precio">Sin costo</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            @if($venta !== null)
                                <span class="precio-badge badge-venta">
                                    Gs. {{ number_format($venta, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="sin-precio">Sin precio</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            @if($margen !== null)
                                <span style="font-size:13px;font-weight:600;color:{{ $margen >= 0 ? '#27794a' : '#c0392b' }};">
                                    {{ $margen >= 0 ? '+' : '' }}{{ number_format($margen, 1) }}%
                                </span>
                            @else
                                <span class="sin-precio">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="td-acciones">
                                <button type="button" class="btn btn-sm btn-secondary btn-nuevo-precio"
                                        data-id="{{ $art->id }}"
                                        data-nombre="{{ $art->nombre }}"
                                        data-costo="{{ (int) round($costo) }}"
                                        data-costo-abrev="{{ $art->unidadMedida->abreviatura }}"
                                        data-precio-actual="{{ $venta !== null ? (int) round($venta) : '' }}">
                                    <i data-lucide="tag" style="width:13px;height:13px;"></i>
                                    Nuevo precio
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Modal --}}
<div class="modal-overlay" id="modal-precio">
    <div class="modal-box">
        <button type="button" class="modal-close" id="modal-cerrar">
            <i data-lucide="x" style="width:18px;height:18px;"></i>
        </button>
        <div class="modal-title">
            <i data-lucide="tag" style="width:18px;height:18px;color:#6b5744;"></i>
            Nuevo precio de venta
        </div>

        <p id="modal-articulo-nombre" style="font-size:15px;font-weight:600;color:#2c2117;margin-bottom:16px;"></p>

        <div style="background:#f5f0eb;border-radius:7px;padding:10px 14px;font-size:13px;color:#6b5744;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
            <span>Precio de costo:</span>
            <strong id="modal-costo-val">—</strong>
        </div>

        <form method="POST" action="{{ route('precios-venta.store') }}">
            @csrf
            <input type="hidden" name="articulo_id" id="modal-articulo-id">

            <div class="form-group">
                <label for="modal-precio-input">Nuevo precio de venta (Gs.)</label>
                <div class="input-wrap">
                    <i data-lucide="dollar-sign" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                    <input type="number" id="modal-precio-input" name="precio"
                           min="0" step="any" required placeholder="0.00">
                </div>
            </div>

            <div id="margen-wrap" style="display:none;border-radius:7px;padding:10px 14px;font-size:13px;margin-bottom:4px;">
                Margen sobre costo: <strong id="margen-val">—</strong>
            </div>

            <div style="display:flex;gap:10px;margin-top:24px;">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" style="width:14px;height:14px;"></i>
                    Guardar precio
                </button>
                <button type="button" id="modal-cancelar" class="btn btn-secondary">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const modal            = document.getElementById('modal-precio');
    const btnCerrar        = document.getElementById('modal-cerrar');
    const btnCancelar      = document.getElementById('modal-cancelar');
    const modalNombre      = document.getElementById('modal-articulo-nombre');
    const modalId          = document.getElementById('modal-articulo-id');
    const modalCostoVal    = document.getElementById('modal-costo-val');
    const modalPrecioInput = document.getElementById('modal-precio-input');
    const margenWrap       = document.getElementById('margen-wrap');
    const margenVal        = document.getElementById('margen-val');

    let costoActual = 0;

    function formatGs(value, decimals = 0) {
        const parts = value.toFixed(decimals).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return 'Gs. ' + (decimals > 0 && parts[1] ? parts.join(',') : parts[0]);
    }

    function abrirModal(btn) {
        modalId.value           = btn.dataset.id;
        modalNombre.textContent = btn.dataset.nombre;
        costoActual             = parseFloat(btn.dataset.costo) || 0;
        modalCostoVal.textContent = costoActual > 0
            ? formatGs(costoActual, 2) + ' / ' + btn.dataset.costoAbrev
            : 'Sin datos';
        modalPrecioInput.value  = btn.dataset.precioActual || '';
        actualizarMargen();
        modal.classList.add('open');
        setTimeout(() => modalPrecioInput.focus(), 50);
        lucide.createIcons();
    }

    function cerrarModal() { modal.classList.remove('open'); }

    function actualizarMargen() {
        const precio = parseFloat(modalPrecioInput.value);
        if (costoActual > 0 && !isNaN(precio) && precio > 0) {
            const margen = ((precio - costoActual) / costoActual * 100).toFixed(1);
            const positivo = precio >= costoActual;
            margenVal.textContent        = (positivo ? '+' : '') + margen + '%';
            margenWrap.style.background  = positivo ? '#f0faf3' : '#fff5f5';
            margenWrap.style.border      = '1px solid ' + (positivo ? '#b8dfc6' : '#f5c6c4');
            margenWrap.style.color       = positivo ? '#27794a' : '#c0392b';
            margenWrap.style.display     = 'block';
        } else {
            margenWrap.style.display = 'none';
        }
    }

    document.querySelectorAll('.btn-nuevo-precio').forEach(btn => {
        btn.addEventListener('click', () => abrirModal(btn));
    });

    btnCerrar.addEventListener('click', cerrarModal);
    btnCancelar.addEventListener('click', cerrarModal);
    modal.addEventListener('click', e => { if (e.target === modal) cerrarModal(); });
    modalPrecioInput.addEventListener('input', actualizarMargen);
})();
</script>

@endsection
