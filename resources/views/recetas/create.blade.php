@extends('layouts.app')

@section('titulo', 'Nueva Receta')

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
    .resultado-banner { display:none; background:#f0faf3; border:1px solid #b8dfc6; border-radius:8px; padding:12px 16px; font-size:13px; color:#27794a; margin-bottom:20px; align-items:center; gap:8px; }
    .resultado-banner strong { font-weight:600; }
    .combo-wrap { position:relative; }
    .combo-input {
        width:100%; padding:10px 14px; border:1px solid #d8cfc7; border-radius:7px;
        background:#fdfaf7; font-size:14px; font-family:inherit; color:#2c2117; outline:none;
        transition:border-color 0.2s, box-shadow 0.2s;
    }
    .combo-input:focus { border-color:#a08c78; background:#fff; box-shadow:0 0 0 3px rgba(160,140,120,0.15); }
    .receta-table .combo-input { padding:8px 10px; font-size:13px; min-width:180px; }
    .combo-list {
        display:none; position:absolute; top:calc(100% + 4px); left:0; right:0;
        background:#fff; border:1px solid #d8cfc7; border-radius:8px;
        box-shadow:0 8px 24px rgba(0,0,0,0.12); max-height:220px; overflow-y:auto; z-index:20;
    }
    .combo-list.open { display:block; }
    .combo-item { padding:9px 14px; font-size:13px; cursor:pointer; display:flex; justify-content:space-between; gap:8px; }
    .combo-item:hover { background:#f5f0eb; }
    .combo-item .combo-tipo { color:#a08c78; font-size:12px; white-space:nowrap; }
    .combo-empty { padding:12px 14px; font-size:13px; color:#c4b8ac; }
</style>

<div class="section-header">
    <div>
        <h1>Nueva receta</h1>
        <p style="font-size:13px;color:#a08c78;margin-top:4px;">Seleccioná el artículo de producción y agregá sus ingredientes.</p>
    </div>
    <a href="{{ route('recetas.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
        Volver
    </a>
</div>

<form method="POST" action="{{ route('recetas.store') }}" id="form-receta">
    @csrf

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
            <label for="articulo-prod-buscador">Artículo de producción</label>
            <div class="combo-wrap">
                <input type="text" id="articulo-prod-buscador" class="combo-input" autocomplete="off"
                       placeholder="Buscar artículo de producción…">
                <select id="articulo_id" name="articulo_id" required style="display:none;">
                    <option value="">— Seleccioná un artículo —</option>
                    @foreach($articulosProduccion as $art)
                        <option value="{{ $art->id }}"
                                data-nombre="{{ $art->nombre }}"
                                data-unidad="{{ $art->unidadMedida->nombre }}"
                                data-unidad-abrev="{{ $art->unidadMedida->abreviatura }}"
                                {{ old('articulo_id', $preseleccionado) == $art->id ? 'selected' : '' }}>
                            {{ $art->nombre }}
                        </option>
                    @endforeach
                </select>
                <div class="combo-list" id="articulo-prod-lista"></div>
            </div>
            @if($articulosProduccion->isEmpty())
                <p style="font-size:12px;color:#a08c78;margin-top:8px;">
                    <i data-lucide="info" style="width:13px;height:13px;display:inline-block;vertical-align:middle;"></i>
                    No hay artículos de tipo producción activos.
                </p>
            @endif
            @error('articulo_id') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Ingredientes --}}
    <div class="card" style="padding:0; overflow:visible; margin-bottom:24px;">
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

            <div id="empty-items" style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:32px 0;color:#c4b8ac;font-size:13px;">
                <i data-lucide="list-plus" style="width:28px;height:28px;color:#ddd5cc;"></i>
                <span>Aún no hay ingredientes. Usá el botón para agregar.</span>
            </div>
        </div>
    </div>

    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary">
            <i data-lucide="save" style="width:14px;height:14px;"></i>
            Guardar receta
        </button>
        <a href="{{ route('recetas.index') }}" class="btn btn-secondary">Cancelar</a>
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

    // Mapa rápido id → artículo
    const articuloMap = {};
    articulos.forEach(a => articuloMap[a.id] = a);

    let rowIndex = 0;
    const tbody      = document.getElementById('items-body');
    const emptyMsg   = document.getElementById('empty-items');
    const btnAgregar = document.getElementById('btn-agregar');

    // Banner resultado
    const selProd        = document.getElementById('articulo_id');
    const banner         = document.getElementById('resultado-banner');
    const bannerNombre   = document.getElementById('resultado-nombre');
    const bannerUnidad   = document.getElementById('resultado-unidad');

    selProd.addEventListener('change', function () {
        const opt = selProd.options[selProd.selectedIndex];
        if (selProd.value) {
            bannerNombre.textContent = opt.dataset.nombre;
            bannerUnidad.textContent = opt.dataset.unidad + ' (' + opt.dataset.unidadAbrev + ')';
            banner.style.display = 'flex';
        } else {
            banner.style.display = 'none';
        }
        lucide.createIcons();
    });

    // Inicializar banner si hay valor preseleccionado
    if (selProd.value) selProd.dispatchEvent(new Event('change'));

    // ── Combobox: artículo de producción ─────────────────────────────────
    const prodBuscador = document.getElementById('articulo-prod-buscador');
    const prodLista     = document.getElementById('articulo-prod-lista');
    let prodFiltradas   = [];

    function etiquetaProd(opt) {
        return opt.dataset.nombre;
    }

    function renderListaProd(filtro) {
        const term = filtro.trim().toLowerCase();
        const opciones = Array.from(selProd.options).filter(o => o.value !== '');
        prodFiltradas = opciones.filter(o => o.dataset.nombre.toLowerCase().includes(term));

        prodLista.innerHTML = '';
        if (!prodFiltradas.length) {
            prodLista.innerHTML = '<div class="combo-empty">Sin coincidencias.</div>';
        } else {
            prodFiltradas.forEach(o => {
                const item = document.createElement('div');
                item.className = 'combo-item';
                item.innerHTML = `<span>${o.dataset.nombre}</span><span class="combo-tipo">${o.dataset.unidadAbrev}</span>`;
                item.addEventListener('click', () => seleccionarProd(o));
                prodLista.appendChild(item);
            });
        }
        prodLista.classList.add('open');
    }

    function seleccionarProd(opcion) {
        selProd.value = opcion.value;
        prodBuscador.value = etiquetaProd(opcion);
        prodLista.classList.remove('open');
        selProd.dispatchEvent(new Event('change'));
    }

    prodBuscador.addEventListener('focus', function () {
        renderListaProd('');
        prodBuscador.select();
    });
    prodBuscador.addEventListener('input', function () {
        renderListaProd(prodBuscador.value);
    });
    prodBuscador.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            prodLista.classList.remove('open');
            prodBuscador.blur();
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (prodFiltradas.length) seleccionarProd(prodFiltradas[0]);
        }
    });
    prodBuscador.addEventListener('blur', function () {
        setTimeout(function () {
            prodLista.classList.remove('open');
            const seleccionado = selProd.options[selProd.selectedIndex];
            prodBuscador.value = selProd.value ? etiquetaProd(seleccionado) : '';
        }, 150);
    });

    if (selProd.value) {
        prodBuscador.value = etiquetaProd(selProd.options[selProd.selectedIndex]);
    }

    function buildArticuloOptions(selected = '') {
        return articulos.map(a =>
            `<option value="${a.id}" data-unidad-id="${a.unidad_id}" data-unidad-nombre="${a.unidad_nombre}" data-unidad-abrev="${a.unidad_abrev}" ${a.id == selected ? 'selected' : ''}>${a.nombre} (${a.tipo})</option>`
        ).join('');
    }

    function toggleEmpty() {
        emptyMsg.style.display = tbody.querySelectorAll('tr').length === 0 ? 'flex' : 'none';
    }

    function addRow(data = {}) {
        const i  = rowIndex++;
        const tr = document.createElement('tr');

        // Determinar unidad inicial si hay artículo preseleccionado
        const art = data.articulo_id ? articuloMap[data.articulo_id] : null;
        const unidadId    = art ? art.unidad_id    : '';
        const unidadNombre= art ? art.unidad_nombre : '';
        const unidadAbrev = art ? art.unidad_abrev  : '';

        tr.innerHTML = `
            <td>
                <div class="combo-wrap item-combo-wrap">
                    <input type="text" class="combo-input item-articulo-buscador" autocomplete="off"
                           placeholder="Buscar artículo…" value="${art ? (art.nombre + ' (' + art.tipo + ')') : ''}">
                    <select name="items[${i}][articulo_id]" required class="item-articulo-sel" style="display:none;">
                        <option value="">— Artículo —</option>
                        ${buildArticuloOptions(data.articulo_id || '')}
                    </select>
                    <div class="combo-list item-articulo-lista"></div>
                </div>
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

        // ── Combobox: artículo del ingrediente ────────────────────────────
        const itemSel      = tr.querySelector('.item-articulo-sel');
        const itemBuscador  = tr.querySelector('.item-articulo-buscador');
        const itemLista     = tr.querySelector('.item-articulo-lista');
        let itemFiltradas   = [];

        function etiquetaItem(a) {
            return a.nombre + ' (' + a.tipo + ')';
        }

        function actualizarUnidadFila() {
            const art = articuloMap[itemSel.value];
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
        }

        function renderListaItem(filtro) {
            const term = filtro.trim().toLowerCase();
            itemFiltradas = articulos.filter(a =>
                a.nombre.toLowerCase().includes(term) || a.tipo.toLowerCase().includes(term)
            );

            itemLista.innerHTML = '';
            if (!itemFiltradas.length) {
                itemLista.innerHTML = '<div class="combo-empty">Sin coincidencias.</div>';
            } else {
                itemFiltradas.forEach(a => {
                    const opcion = document.createElement('div');
                    opcion.className = 'combo-item';
                    opcion.innerHTML = `<span>${a.nombre}</span><span class="combo-tipo">${a.tipo}</span>`;
                    opcion.addEventListener('click', () => seleccionarItem(a));
                    itemLista.appendChild(opcion);
                });
            }
            itemLista.classList.add('open');
        }

        function seleccionarItem(a) {
            itemSel.value = a.id;
            itemBuscador.value = etiquetaItem(a);
            itemLista.classList.remove('open');
            actualizarUnidadFila();
        }

        itemBuscador.addEventListener('focus', function () {
            renderListaItem('');
            itemBuscador.select();
        });
        itemBuscador.addEventListener('input', function () {
            renderListaItem(itemBuscador.value);
        });
        itemBuscador.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                itemLista.classList.remove('open');
                itemBuscador.blur();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (itemFiltradas.length) seleccionarItem(itemFiltradas[0]);
            }
        });
        itemBuscador.addEventListener('blur', function () {
            setTimeout(function () {
                itemLista.classList.remove('open');
                const art = articuloMap[itemSel.value];
                itemBuscador.value = art ? etiquetaItem(art) : '';
            }, 150);
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

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.combo-wrap')) {
            document.querySelectorAll('.combo-list.open').forEach(l => l.classList.remove('open'));
        }
    });

    // Restaurar filas si hubo error de validación
    @if(old('items'))
        @foreach(old('items') as $item)
            addRow({
                articulo_id:      '{{ $item['articulo_id'] ?? '' }}',
                cantidad:         '{{ $item['cantidad'] ?? '' }}',
                estado_id:        '{{ $item['estado_id'] ?? 1 }}',
            });
        @endforeach
    @else
        addRow();
    @endif
})();
</script>

@endsection
