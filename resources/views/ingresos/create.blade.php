@extends('layouts.app')

@section('titulo', 'Nuevo Ingreso')

@section('contenido')

<style>
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.45); z-index: 1000;
        align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 12px; padding: 32px 36px;
        width: 100%; max-width: 440px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18); position: relative;
    }
    .modal-title { font-size:16px; font-weight:600; color:#1a1208; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .modal-close { position:absolute; top:16px; right:16px; background:none; border:none; cursor:pointer; color:#a08c78; padding:4px; border-radius:5px; display:flex; align-items:center; transition:color 0.15s,background 0.15s; }
    .modal-close:hover { color:#2c2117; background:#f5f0eb; }
    .modal-error { background:#fff5f5; border:1px solid #f5c6c4; border-radius:7px; padding:10px 14px; font-size:13px; color:#c0392b; margin-bottom:16px; display:none; }
    .unidad-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f5f0eb; color: #6b5744; border: 1px solid #e8e0d8;
        border-radius: 6px; padding: 9px 14px; font-size: 14px; font-weight: 500;
        white-space: nowrap;
    }
    .calc-box { background:#f5f0eb; border-radius:8px; padding:14px 18px; font-size:13px; color:#6b5744; margin-top:16px; display:none; }
    .calc-row { display:flex; justify-content:space-between; padding:3px 0; }
    .calc-label { color:#a08c78; }
    .calc-val { font-weight:600; color:#2c2117; }
</style>

<div class="section-header">
    <div>
        <h1>Nuevo ingreso</h1>
        <p style="font-size:13px;color:#a08c78;margin-top:4px;">Registrá la entrada de stock de un artículo.</p>
    </div>
    <a href="{{ route('ingresos.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
        Volver
    </a>
</div>

<div class="card" style="max-width: 540px;">
    <form method="POST" action="{{ route('ingresos.store') }}">
        @csrf

        <div class="form-group">
            <label for="fecha">Fecha</label>
            <div class="input-wrap">
                <i data-lucide="calendar" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                <input type="date" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
            </div>
            @error('fecha') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="articulo_id">Artículo</label>
            <div style="display:flex;gap:8px;">
                <select id="articulo_id" name="articulo_id" required style="flex:1;">
                    <option value="">— Seleccioná un artículo —</option>
                    @foreach($articulos as $art)
                        <option value="{{ $art->id }}"
                                data-unidad-abrev="{{ $art->unidadMedida->abreviatura }}"
                                data-unidad-nombre="{{ $art->unidadMedida->nombre }}"
                                data-es-produccion="{{ $art->esProduccion() ? '1' : '0' }}"
                                {{ old('articulo_id') == $art->id ? 'selected' : '' }}>
                            {{ $art->nombre }} ({{ $art->tipoArticulo->nombre }})
                        </option>
                    @endforeach
                </select>
                <button type="button" id="btn-nuevo-articulo" class="btn btn-secondary" style="white-space:nowrap;">
                    <i data-lucide="plus" style="width:14px;height:14px;"></i>
                    Nuevo
                </button>
            </div>
            @error('articulo_id') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr auto;gap:12px;align-items:end;">
            <div class="form-group" style="margin-bottom:0;">
                <label for="cantidad">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad"
                       value="{{ old('cantidad') }}" min="0.0001" step="any" required placeholder="0.00">
                @error('cantidad') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
            </div>
            <div id="unidad-wrap" style="margin-bottom:1px;">
                <span class="unidad-badge" id="unidad-badge" style="display:none;">
                    <i data-lucide="ruler" style="width:13px;height:13px;color:#a08c78;"></i>
                    <span id="unidad-texto">—</span>
                </span>
            </div>
        </div>

        {{-- Precio manual (artículos no producción) --}}
        <div id="precio-manual-wrap" class="form-group" style="margin-top:20px;">
            <label for="precio">Precio total pagado (Gs.)</label>
            <div class="input-wrap">
                <i data-lucide="dollar-sign" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                <input type="number" id="precio" name="precio"
                       value="{{ old('precio') }}" min="0" step="any" placeholder="0.00">
            </div>
            @error('precio') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div class="calc-box" id="calc-box">
            <div class="calc-row">
                <span class="calc-label">Precio de costo unitario</span>
                <span class="calc-val" id="calc-costo">—</span>
            </div>
        </div>

        {{-- Costo calculado (artículos producción) --}}
        <div id="precio-produccion-wrap" style="display:none;margin-top:16px;background:#f0faf3;border:1px solid #b8dfc6;border-radius:8px;padding:14px 18px;font-size:13px;">
            <div style="display:flex;align-items:center;gap:6px;font-weight:600;color:#27794a;margin-bottom:6px;">
                <i data-lucide="calculator" style="width:14px;height:14px;"></i>
                Costo calculado de la receta
            </div>
            <div style="display:flex;gap:24px;flex-wrap:wrap;">
                <div><span style="color:#a08c78;">Por unidad: </span><strong id="costo-unitario-val" style="color:#1a5c35;">—</strong></div>
                <div><span style="color:#a08c78;">Total: </span><strong id="costo-total-val" style="color:#1a5c35;">—</strong></div>
            </div>
            <p id="sin-receta-msg" style="display:none;color:#a08c78;margin-top:6px;font-size:12px;">
                <i data-lucide="info" style="width:12px;height:12px;display:inline-block;vertical-align:middle;"></i>
                Este artículo no tiene receta activa. Configurá su receta para calcular el costo automáticamente.
            </p>
        </div>

        <div id="receta-preview" style="display:none;background:#fff8f0;border:1px solid #e8d8c0;border-radius:8px;padding:14px 18px;font-size:13px;margin-top:12px;">
            <div style="display:flex;align-items:center;gap:6px;font-weight:600;color:#6b5744;margin-bottom:10px;">
                <i data-lucide="chef-hat" style="width:14px;height:14px;"></i>
                Ingredientes necesarios para esta producción
            </div>
            <div id="receta-items"></div>
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label for="observacion">Observación <span style="color:#c4b8ac;font-weight:400;">(opcional)</span></label>
            <textarea id="observacion" name="observacion" placeholder="Proveedor, remito, notas...">{{ old('observacion') }}</textarea>
            @error('observacion') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div style="margin-top:28px;display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" style="width:14px;height:14px;"></i>
                Registrar ingreso
            </button>
            <a href="{{ route('ingresos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

{{-- Modal: Nuevo artículo rápido --}}
<div class="modal-overlay" id="modal-articulo">
    <div class="modal-box">
        <button type="button" class="modal-close" id="modal-cerrar">
            <i data-lucide="x" style="width:18px;height:18px;"></i>
        </button>
        <div class="modal-title">
            <i data-lucide="package-plus" style="width:18px;height:18px;color:#6b5744;"></i>
            Nuevo artículo
        </div>
        <div class="modal-error" id="modal-error"></div>
        <div class="form-group">
            <label for="m-nombre">Nombre</label>
            <div class="input-wrap">
                <i data-lucide="package" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                <input type="text" id="m-nombre" placeholder="Ej: Arroz integral">
            </div>
        </div>
        <div class="form-group">
            <label for="m-tipo">Tipo de artículo</label>
            <select id="m-tipo">
                <option value="">— Seleccioná —</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="m-unidad">Unidad de medida</label>
            <select id="m-unidad">
                <option value="">— Seleccioná —</option>
                @foreach($unidades as $um)
                    <option value="{{ $um->id }}" data-abrev="{{ $um->abreviatura }}" data-nombre="{{ $um->nombre }}">
                        {{ $um->nombre }} ({{ $um->abreviatura }})
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;gap:10px;margin-top:24px;">
            <button type="button" id="modal-guardar" class="btn btn-primary">
                <i data-lucide="save" style="width:14px;height:14px;"></i>
                Crear artículo
            </button>
            <button type="button" id="modal-cancelar" class="btn btn-secondary">Cancelar</button>
        </div>
    </div>
</div>

@php
    $recetasJs = $recetasPreview;
@endphp

<script>
(function () {
    const recetasPreview = @json($recetasJs);

    const selArticulo   = document.getElementById('articulo_id');
    const unidadBadge   = document.getElementById('unidad-badge');
    const unidadTexto   = document.getElementById('unidad-texto');
    const inputCantidad = document.getElementById('cantidad');
    const inputPrecio   = document.getElementById('precio');
    const calcBox       = document.getElementById('calc-box');
    const calcCosto     = document.getElementById('calc-costo');
    const recetaPreview        = document.getElementById('receta-preview');
    const recetaItems          = document.getElementById('receta-items');
    const precioManualWrap     = document.getElementById('precio-manual-wrap');
    const precioProduccionWrap = document.getElementById('precio-produccion-wrap');
    const costoUnitarioVal     = document.getElementById('costo-unitario-val');
    const costoTotalVal        = document.getElementById('costo-total-val');
    const sinRecetaMsg         = document.getElementById('sin-receta-msg');

    let abrevUnidad = '';

    function formatGs(value, decimals = 0) {
        const parts = value.toFixed(decimals).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return 'Gs. ' + (decimals > 0 && parts[1] ? parts.join(',') : parts[0]);
    }

    function esProduccion() {
        const opt = selArticulo.options[selArticulo.selectedIndex];
        return selArticulo.value && opt.dataset.esProduccion === '1';
    }

    function actualizarUnidad() {
        const opt = selArticulo.options[selArticulo.selectedIndex];
        if (selArticulo.value) {
            abrevUnidad = opt.dataset.unidadAbrev || '';
            unidadTexto.textContent = opt.dataset.unidadNombre + ' (' + abrevUnidad + ')';
            unidadBadge.style.display = 'inline-flex';
        } else {
            unidadBadge.style.display = 'none';
            abrevUnidad = '';
        }
        if (esProduccion()) {
            precioManualWrap.style.display = 'none';
            calcBox.style.display = 'none';
            precioProduccionWrap.style.display = 'block';
            actualizarCostoProduccion();
        } else {
            precioManualWrap.style.display = 'block';
            precioProduccionWrap.style.display = 'none';
            recetaPreview.style.display = 'none';
            actualizarCalculo();
        }
        actualizarPreviewReceta();
        lucide.createIcons();
    }

    function actualizarCalculo() {
        const cantidad = parseFloat(inputCantidad.value);
        const precio   = parseFloat(inputPrecio.value);
        if (!selArticulo.value || isNaN(cantidad) || isNaN(precio) || cantidad <= 0) {
            calcBox.style.display = 'none';
            return;
        }
        calcCosto.textContent = formatGs(precio / cantidad, 2) + ' / ' + abrevUnidad;
        calcBox.style.display = 'block';
    }

    function actualizarCostoProduccion() {
        if (!esProduccion()) return;
        const articuloId = selArticulo.value;
        const items      = recetasPreview[articuloId];
        const cantidad   = parseFloat(inputCantidad.value);
        if (!items) {
            sinRecetaMsg.style.display = 'block';
            costoUnitarioVal.textContent = '—';
            costoTotalVal.textContent    = '—';
            return;
        }
        sinRecetaMsg.style.display = 'none';
        let costoUnitario = 0;
        items.forEach(item => { costoUnitario += item.cantidad * item.costo; });
        costoUnitarioVal.textContent = formatGs(costoUnitario, 2) + ' / ' + abrevUnidad;
        costoTotalVal.textContent = (!isNaN(cantidad) && cantidad > 0)
            ? formatGs(costoUnitario * cantidad, 0)
            : '—';
    }

    function actualizarPreviewReceta() {
        const articuloId = selArticulo.value;
        const cantidad   = parseFloat(inputCantidad.value);
        const items      = recetasPreview[articuloId];

        if (!articuloId || !items || isNaN(cantidad) || cantidad <= 0) {
            recetaPreview.style.display = 'none';
            return;
        }

        let html = '';
        items.forEach(item => {
            const necesario = cantidad * item.cantidad;
            const ok = item.stock >= necesario;
            const estado = ok
                ? '<span style="color:#27794a;">✓ OK</span>'
                : '<span style="color:#c0392b;font-weight:600;">✗ faltan ' + (necesario - item.stock).toFixed(3) + ' ' + item.unidad + '</span>';
            html += `<div style="display:flex;justify-content:space-between;align-items:center;padding:5px 0;border-bottom:1px solid #f0e8dc;">
                <span style="color:#4a3a2a;">${item.nombre}</span>
                <span style="display:flex;align-items:center;gap:12px;font-size:12px;">
                    <span style="color:#a08c78;">${necesario.toFixed(3)} ${item.unidad} <span style="color:#c4b8ac;">(stock: ${item.stock})</span></span>
                    ${estado}
                </span>
            </div>`;
        });
        recetaItems.innerHTML = html;
        recetaPreview.style.display = 'block';
        lucide.createIcons();
    }

    selArticulo.addEventListener('change', actualizarUnidad);
    inputCantidad.addEventListener('input', () => {
        if (esProduccion()) { actualizarCostoProduccion(); actualizarPreviewReceta(); }
        else actualizarCalculo();
    });
    inputPrecio.addEventListener('input', actualizarCalculo);

    if (selArticulo.value) actualizarUnidad();

    // ── Modal ──────────────────────────────────────────────────────────────
    const modal       = document.getElementById('modal-articulo');
    const modalError  = document.getElementById('modal-error');
    const btnNuevo    = document.getElementById('btn-nuevo-articulo');
    const btnCerrar   = document.getElementById('modal-cerrar');
    const btnCancelar = document.getElementById('modal-cancelar');
    const btnGuardar  = document.getElementById('modal-guardar');
    const mNombre     = document.getElementById('m-nombre');
    const mTipo       = document.getElementById('m-tipo');
    const mUnidad     = document.getElementById('m-unidad');

    function abrirModal()  { mNombre.value=''; mTipo.value=''; mUnidad.value=''; modalError.style.display='none'; modal.classList.add('open'); mNombre.focus(); lucide.createIcons(); }
    function cerrarModal() { modal.classList.remove('open'); }

    btnNuevo.addEventListener('click', abrirModal);
    btnCerrar.addEventListener('click', cerrarModal);
    btnCancelar.addEventListener('click', cerrarModal);
    modal.addEventListener('click', e => { if (e.target === modal) cerrarModal(); });

    btnGuardar.addEventListener('click', async function () {
        modalError.style.display = 'none';
        if (!mNombre.value.trim() || !mTipo.value || !mUnidad.value) {
            modalError.textContent = 'Completá todos los campos.';
            modalError.style.display = 'block';
            return;
        }
        btnGuardar.disabled = true;
        try {
            const res  = await fetch('{{ route('articulos.rapido') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ nombre: mNombre.value.trim(), tipo_articulo_id: mTipo.value, unidad_medida_id: mUnidad.value }),
            });
            const data = await res.json();
            if (!res.ok) { modalError.textContent = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Error.'); modalError.style.display='block'; return; }
            const opt = document.createElement('option');
            opt.value = data.id;
            opt.textContent = data.nombre + ' (' + data.tipo + ')';
            opt.dataset.unidadAbrev  = data.unidad_abrev;
            opt.dataset.unidadNombre = data.unidad_nombre;
            opt.selected = true;
            selArticulo.appendChild(opt);
            actualizarUnidad();
            cerrarModal();
        } catch(e) {
            modalError.textContent = 'Error de conexión.';
            modalError.style.display = 'block';
        } finally {
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = '<i data-lucide="save" style="width:14px;height:14px;"></i> Crear artículo';
            lucide.createIcons();
        }
    });

    lucide.createIcons();
})();
</script>

@endsection
