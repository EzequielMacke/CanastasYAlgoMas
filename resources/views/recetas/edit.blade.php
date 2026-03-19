@extends('layouts.app')

@section('titulo', 'Editar Receta')

@section('contenido')

<style>
    .receta-table { width:100%; border-collapse:collapse; margin-top:8px; }
    .receta-table th { text-align:left; font-size:11px; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; color:#a08c78; padding:8px 10px; border-bottom:1px solid #ede7df; }
    .receta-table td { padding:8px 6px; vertical-align:middle; border-bottom:1px solid #f5f0eb; }
    .receta-table tr:last-child td { border-bottom:none; }
    .receta-table select, .receta-table input[type="number"] { padding:8px 10px; font-size:13px; }
    .receta-table input[type="number"] { width:90px; }
    .btn-remove-row { background:none; border:none; cursor:pointer; color:#c4b8ac; padding:4px; border-radius:5px; display:flex; align-items:center; transition:color 0.15s,background 0.15s; }
    .btn-remove-row:hover { color:#c0392b; background:#fff5f5; }
    .unidad-cell { display:inline-flex; align-items:center; gap:5px; background:#f5f0eb; color:#6b5744; border:1px solid #e8e0d8; border-radius:6px; padding:6px 10px; font-size:13px; font-weight:500; min-width:80px; }
    .resultado-banner { background:#f0faf3; border:1px solid #b8dfc6; border-radius:8px; padding:12px 16px; font-size:13px; color:#27794a; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .resultado-banner strong { font-weight:600; }
</style>

@php $backTab = $receta->estado_id === 1 ? 'activas' : 'inactivas'; @endphp

@php
    $itemsJs = old('items')
        ? collect(old('items'))->map(fn($i) => [
            'articulo_id' => $i['articulo_id'] ?? null,
            'cantidad'    => $i['cantidad'] ?? null,
            'estado_id'   => $i['estado_id'] ?? 1,
          ])
        : $receta->items->map(fn($i) => [
            'articulo_id' => $i->articulo_id,
            'cantidad'    => $i->cantidad,
            'estado_id'   => $i->estado_id,
          ]);
@endphp

<div class="section-header">
    <div>
        <h1>Editar receta</h1>
        <p style="font-size:13px;color:#a08c78;margin-top:4px;">{{ $receta->articulo->nombre }}</p>
    </div>
    <a href="{{ route('recetas.index', ['tab' => $backTab]) }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
        Volver
    </a>
</div>

<form method="POST" action="{{ route('recetas.update', $receta) }}" id="form-receta">
    @csrf @method('PUT')

    {{-- Artículo de producción --}}
    <div class="card" style="max-width:560px; margin-bottom:24px;">

        {{-- Banner resultado --}}
        <div class="resultado-banner" id="resultado-banner">
            <i data-lucide="chef-hat" style="width:16px;height:16px;flex-shrink:0;"></i>
            Resultado: <strong id="resultado-nombre"></strong>
            <span style="color:#a08c78;">por</span>
            <strong id="resultado-unidad"></strong>
        </div>

        <div class="form-group" style="margin-bottom:0;">
            <label for="articulo_id">Artículo de producción</label>
            <select id="articulo_id" name="articulo_id" required>
                <option value="">— Seleccioná un artículo —</option>
                @foreach($articulosProduccion as $art)
                    <option value="{{ $art->id }}"
                            data-nombre="{{ $art->nombre }}"
                            data-unidad="{{ $art->unidadMedida->nombre }}"
                            data-unidad-abrev="{{ $art->unidadMedida->abreviatura }}"
                            {{ old('articulo_id', $receta->articulo_id) == $art->id ? 'selected' : '' }}>
                        {{ $art->nombre }}
                    </option>
                @endforeach
            </select>
            @error('articulo_id') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Ingredientes --}}
    <div class="card" style="padding:0; overflow:hidden; margin-bottom:24px;">
        <div style="padding:20px 28px 16px; border-bottom:1px solid #ede7df; display:flex; align-items:center; justify-content:space-between;">
            <div>
                <h2 style="font-size:15px;font-weight:600;color:#1a1208;">Ingredientes</h2>
                <p style="font-size:12px;color:#a08c78;margin-top:2px;">La unidad de medida se carga automáticamente del artículo.</p>
            </div>
            <button type="button" id="btn-agregar" class="btn btn-secondary">
                <i data-lucide="plus" style="width:14px;height:14px;"></i>
                Agregar ingrediente
            </button>
        </div>

        <div style="padding:16px 20px 24px;">
            @error('items') <p class="field-error" style="margin-bottom:12px;"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror

            <table class="receta-table" id="tabla-items">
                <thead>
                    <tr>
                        <th>Artículo</th>
                        <th style="width:110px;">Cantidad</th>
                        <th style="width:130px;">Unidad</th>
                        <th style="width:120px;">Estado</th>
                        <th style="width:40px;"></th>
                    </tr>
                </thead>
                <tbody id="items-body"></tbody>
            </table>

            <div id="empty-items" style="display:none;flex-direction:column;align-items:center;gap:8px;padding:32px 0;color:#c4b8ac;font-size:13px;">
                <i data-lucide="list-plus" style="width:28px;height:28px;color:#ddd5cc;"></i>
                <span>Aún no hay ingredientes. Usá el botón para agregar.</span>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary">
            <i data-lucide="save" style="width:14px;height:14px;"></i>
            Guardar cambios
        </button>
        <a href="{{ route('recetas.index', ['tab' => $backTab]) }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

@php
    $articulosJs = $articulos->map(fn($a) => [
        'id'           => $a->id,
        'nombre'       => $a->nombre,
        'tipo'         => $a->tipoArticulo->nombre,
        'unidad_id'    => $a->unidad_medida_id,
        'unidad_nombre'=> $a->unidadMedida->nombre,
        'unidad_abrev' => $a->unidadMedida->abreviatura,
    ]);
@endphp

<script>
(function () {
    const articulos = @json($articulosJs);

    const itemsExistentes = @json($itemsJs);

    const articuloMap = {};
    articulos.forEach(a => articuloMap[a.id] = a);

    let rowIndex = 0;
    const tbody      = document.getElementById('items-body');
    const emptyMsg   = document.getElementById('empty-items');
    const btnAgregar = document.getElementById('btn-agregar');

    // Banner resultado
    const selProd      = document.getElementById('articulo_id');
    const bannerNombre = document.getElementById('resultado-nombre');
    const bannerUnidad = document.getElementById('resultado-unidad');

    function actualizarBanner() {
        const opt = selProd.options[selProd.selectedIndex];
        if (selProd.value) {
            bannerNombre.textContent = opt.dataset.nombre;
            bannerUnidad.textContent = opt.dataset.unidad + ' (' + opt.dataset.unidadAbrev + ')';
        }
        lucide.createIcons();
    }

    selProd.addEventListener('change', actualizarBanner);
    actualizarBanner(); // inicializar con valor existente

    function buildArticuloOptions(selected = '') {
        return articulos.map(a =>
            `<option value="${a.id}" data-unidad-id="${a.unidad_id}" data-unidad-nombre="${a.unidad_nombre}" data-unidad-abrev="${a.unidad_abrev}" ${a.id == selected ? 'selected' : ''}>${a.nombre} (${a.tipo})</option>`
        ).join('');
    }

    function toggleEmpty() {
        emptyMsg.style.display = tbody.querySelectorAll('tr').length === 0 ? 'flex' : 'none';
    }

    function addRow(data = {}) {
        const i   = rowIndex++;
        const tr  = document.createElement('tr');
        const art = data.articulo_id ? articuloMap[data.articulo_id] : null;
        const unidadId     = art ? art.unidad_id     : '';
        const unidadNombre = art ? art.unidad_nombre : '';
        const unidadAbrev  = art ? art.unidad_abrev  : '';

        tr.innerHTML = `
            <td>
                <select name="items[${i}][articulo_id]" required style="min-width:180px;" class="item-articulo-sel">
                    <option value="">— Artículo —</option>
                    ${buildArticuloOptions(data.articulo_id || '')}
                </select>
            </td>
            <td>
                <input type="number" name="items[${i}][cantidad]" value="${data.cantidad || ''}"
                       min="0.001" step="0.001" required placeholder="0.000">
            </td>
            <td>
                <input type="hidden" name="items[${i}][unidad_medida_id]" class="item-unidad-hidden" value="${unidadId}">
                <span class="unidad-cell item-unidad-badge" style="${unidadId ? '' : 'color:#c4b8ac;border-style:dashed;'}">
                    ${unidadId ? `<i data-lucide="ruler" style="width:12px;height:12px;color:#a08c78;"></i> ${unidadNombre} (${unidadAbrev})` : '— seleccioná —'}
                </span>
            </td>
            <td>
                <select name="items[${i}][estado_id]" required>
                    <option value="1" ${(data.estado_id == 1 || !data.estado_id) ? 'selected' : ''}>Activo</option>
                    <option value="2" ${data.estado_id == 2 ? 'selected' : ''}>Inactivo</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn-remove-row" title="Eliminar">
                    <i data-lucide="trash-2" style="width:15px;height:15px;"></i>
                </button>
            </td>
        `;

        tr.querySelector('.item-articulo-sel').addEventListener('change', function () {
            const art    = articuloMap[this.value];
            const hidden = tr.querySelector('.item-unidad-hidden');
            const badge  = tr.querySelector('.item-unidad-badge');
            if (art) {
                hidden.value = art.unidad_id;
                badge.style.borderStyle = 'solid';
                badge.style.color = '#6b5744';
                badge.innerHTML = `<i data-lucide="ruler" style="width:12px;height:12px;color:#a08c78;"></i> ${art.unidad_nombre} (${art.unidad_abrev})`;
            } else {
                hidden.value = '';
                badge.style.borderStyle = 'dashed';
                badge.style.color = '#c4b8ac';
                badge.innerHTML = '— seleccioná —';
            }
            lucide.createIcons();
        });

        tr.querySelector('.btn-remove-row').addEventListener('click', function () {
            tr.remove();
            toggleEmpty();
            lucide.createIcons();
        });

        tbody.appendChild(tr);
        toggleEmpty();
        lucide.createIcons();
    }

    btnAgregar.addEventListener('click', () => addRow());

    itemsExistentes.forEach(item => addRow(item));
    if (itemsExistentes.length === 0) toggleEmpty();
})();
</script>

@endsection
