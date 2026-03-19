@extends('layouts.catalogo')

@push('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #6b5744;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 24px;
        transition: color 0.15s;
    }

    .back-link:hover { color: #2c2117; }

    .detalle-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 36px;
        align-items: start;
        background: #fff;
        border: 1px solid #e8e0d8;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(44,33,23,0.07);
    }

    /* ── Foto ── */
    .detalle-foto-wrap {
        position: relative;
        background: #f5f0eb;
    }

    .detalle-foto {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        display: block;
    }

    .detalle-foto-placeholder {
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d8cfc7;
        background: #f5f0eb;
    }

    /* ── Info ── */
    .detalle-info {
        padding: 36px 36px 36px 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .detalle-tipo {
        font-size: 12px;
        font-weight: 600;
        color: #a08c78;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .detalle-nombre {
        font-size: 28px;
        font-weight: 700;
        color: #1a1208;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .detalle-desc {
        font-size: 14px;
        color: #6b5744;
        line-height: 1.7;
    }

    .detalle-divider {
        border: none;
        border-top: 1px solid #ede7df;
    }

    /* ── Precio y stock ── */
    .detalle-precio {
        font-size: 32px;
        font-weight: 700;
        color: #2c2117;
        letter-spacing: -0.02em;
    }

    .detalle-precio-sin {
        font-size: 16px;
        color: #c4b8ac;
        font-style: italic;
    }

    .detalle-stock-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .badge-grande {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
    }

    .badge-grande.disponible { background: #eef7f1; color: #27794a; border: 1px solid #c2dfc9; }
    .badge-grande.agotado    { background: #fff5f5; color: #c0392b; border: 1px solid #f5c6c4; }
    .badge-grande.servicio   { background: #f0f4ff; color: #3a5bdb; border: 1px solid #c5d0f5; }

    .detalle-stock-cantidad {
        font-size: 13px;
        color: #a08c78;
    }

    .detalle-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detalle-meta-row {
        display: flex;
        gap: 8px;
        font-size: 13px;
    }

    .detalle-meta-label {
        color: #a08c78;
        font-weight: 500;
        min-width: 100px;
        flex-shrink: 0;
    }

    .detalle-meta-valor {
        color: #2c2117;
        font-weight: 500;
    }

    @media (max-width: 680px) {
        .detalle-grid {
            grid-template-columns: 1fr;
        }

        .detalle-info {
            padding: 24px;
        }
    }
</style>
@endpush

@section('contenido')

<a href="{{ route('catalogo.index') }}" class="back-link">
    <i data-lucide="arrow-left" style="width:15px;height:15px;"></i>
    Volver al catálogo
</a>

@php
    $precio   = $articulo->latestPrecioVenta?->precio ?? null;
    $stock    = $articulo->stock?->cantidad ?? 0;
    $servicio = $articulo->esServicio();
@endphp

<div class="detalle-grid">

    {{-- Foto --}}
    <div class="detalle-foto-wrap">
        @if($articulo->foto)
            <img class="detalle-foto" src="{{ asset('storage/' . $articulo->foto) }}" alt="{{ $articulo->nombre }}">
        @else
            <div class="detalle-foto-placeholder">
                <i data-lucide="{{ $servicio ? 'sparkles' : 'package' }}" style="width:80px;height:80px;"></i>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="detalle-info">
        <div>
            <div class="detalle-tipo">{{ $articulo->tipoArticulo->nombre ?? '' }}</div>
            <div class="detalle-nombre">{{ $articulo->nombre }}</div>
        </div>

        @if($articulo->descripcion)
            <p class="detalle-desc">{{ $articulo->descripcion }}</p>
        @endif

        <hr class="detalle-divider">

        {{-- Precio --}}
        @if($servicio)
            <div class="detalle-precio-sin">Precio a consultar</div>
        @elseif($precio)
            <div class="detalle-precio">Gs. {{ number_format($precio, 0, ',', '.') }}</div>
        @else
            <div class="detalle-precio-sin">Precio no disponible</div>
        @endif

        {{-- Stock / disponibilidad --}}
        <div class="detalle-stock-row">
            @if($servicio)
                <span class="badge-grande servicio">
                    <i data-lucide="sparkles" style="width:14px;height:14px;"></i>
                    Servicio disponible
                </span>
            @elseif($stock > 0)
                <span class="badge-grande disponible">
                    <i data-lucide="circle-check" style="width:14px;height:14px;"></i>
                    En stock
                </span>
                <span class="detalle-stock-cantidad">
                    {{ number_format($stock, 2, ',', '.') }} {{ $articulo->unidadMedida?->abreviatura ?? '' }} disponible{{ $stock != 1 ? 's' : '' }}
                </span>
            @else
                <span class="badge-grande agotado">
                    <i data-lucide="x-circle" style="width:14px;height:14px;"></i>
                    Sin stock
                </span>
            @endif
        </div>

        <hr class="detalle-divider">

        {{-- Meta --}}
        <div class="detalle-meta">
            @if($articulo->tipoArticulo)
                <div class="detalle-meta-row">
                    <span class="detalle-meta-label">Categoría</span>
                    <span class="detalle-meta-valor">{{ $articulo->tipoArticulo->nombre }}</span>
                </div>
            @endif
            @if($articulo->unidadMedida && !$servicio)
                <div class="detalle-meta-row">
                    <span class="detalle-meta-label">Unidad</span>
                    <span class="detalle-meta-valor">{{ $articulo->unidadMedida->nombre }} ({{ $articulo->unidadMedida->abreviatura }})</span>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection
