@extends('layouts.catalogo')

@push('styles')
<style>
    .catalogo-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 28px;
    }

    .catalogo-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1a1208;
        letter-spacing: -0.01em;
    }

    .catalogo-header p {
        font-size: 14px;
        color: #a08c78;
        margin-top: 3px;
    }

    .search-wrap {
        position: relative;
        width: 280px;
    }

    .search-wrap svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #c4b8ac;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border: 1px solid #d8cfc7;
        border-radius: 8px;
        background: #fff;
        font-size: 14px;
        font-family: inherit;
        color: #2c2117;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .search-input:focus {
        border-color: #a08c78;
        box-shadow: 0 0 0 3px rgba(160,140,120,0.15);
    }

    /* ── Grid ── */
    .art-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 18px;
    }

    .art-card {
        background: #fff;
        border: 1px solid #e8e0d8;
        border-radius: 12px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.18s, transform 0.18s, border-color 0.18s;
        cursor: pointer;
    }

    .art-card:hover {
        box-shadow: 0 6px 24px rgba(44,33,23,0.12);
        transform: translateY(-2px);
        border-color: #c4b8ac;
    }

    .art-card-foto {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        background: #f5f0eb;
        display: block;
    }

    .art-card-foto-placeholder {
        width: 100%;
        aspect-ratio: 1;
        background: #f5f0eb;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d8cfc7;
    }

    .art-card-body {
        padding: 14px 16px 16px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
    }

    .art-card-tipo {
        font-size: 11px;
        font-weight: 600;
        color: #a08c78;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .art-card-nombre {
        font-size: 15px;
        font-weight: 600;
        color: #1a1208;
        line-height: 1.3;
    }

    .art-card-desc {
        font-size: 12px;
        color: #6b5744;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .art-card-footer {
        margin-top: auto;
        padding-top: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
    }

    .art-precio {
        font-size: 16px;
        font-weight: 700;
        color: #2c2117;
    }

    .art-precio-sin {
        font-size: 13px;
        color: #c4b8ac;
        font-style: italic;
    }

    .badge-stock {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
    }

    .badge-stock.disponible {
        background: #eef7f1;
        color: #27794a;
    }

    .badge-stock.agotado {
        background: #fff5f5;
        color: #c0392b;
    }

    .badge-stock.servicio {
        background: #f0f4ff;
        color: #3a5bdb;
    }

    .empty {
        grid-column: 1/-1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 60px 0;
        color: #c4b8ac;
        font-size: 14px;
    }
</style>
@endpush

@section('contenido')

<div class="catalogo-header">
    <div>
        <h1>Catálogo</h1>
        <p>{{ $articulos->count() }} {{ $articulos->count() === 1 ? 'producto disponible' : 'productos disponibles' }}</p>
    </div>
    <form method="GET" action="{{ route('catalogo.index') }}">
        <div class="search-wrap">
            <i data-lucide="search" style="width:16px;height:16px;"></i>
            <input type="text" name="q" class="search-input" value="{{ $buscar }}" placeholder="Buscar…" autocomplete="off">
        </div>
    </form>
</div>

<div class="art-grid">
    @forelse($articulos as $art)
        @php
            $precio   = $art->latestPrecioVenta?->precio ?? null;
            $stock    = $art->stock?->cantidad ?? 0;
            $servicio = $art->esServicio();
        @endphp
        <a href="{{ route('catalogo.show', $art) }}" class="art-card">
            @if($art->foto)
                <img class="art-card-foto" src="{{ asset('storage/' . $art->foto) }}" alt="{{ $art->nombre }}">
            @else
                <div class="art-card-foto-placeholder">
                    <i data-lucide="{{ $servicio ? 'sparkles' : 'package' }}" style="width:48px;height:48px;"></i>
                </div>
            @endif
            <div class="art-card-body">
                <div class="art-card-tipo">{{ $art->tipoArticulo->nombre ?? '' }}</div>
                <div class="art-card-nombre">{{ $art->nombre }}</div>
                @if($art->descripcion)
                    <div class="art-card-desc">{{ $art->descripcion }}</div>
                @endif
                <div class="art-card-footer">
                    @if($servicio)
                        <span class="art-precio-sin">Servicio</span>
                        <span class="badge-stock servicio">
                            <i data-lucide="sparkles" style="width:10px;height:10px;"></i>
                            Disponible
                        </span>
                    @else
                        @if($precio)
                            <span class="art-precio">Gs. {{ number_format($precio, 0, ',', '.') }}</span>
                        @else
                            <span class="art-precio-sin">Sin precio</span>
                        @endif
                        @if($stock > 0)
                            <span class="badge-stock disponible">
                                <i data-lucide="circle-check" style="width:10px;height:10px;"></i>
                                Disponible
                            </span>
                        @else
                            <span class="badge-stock agotado">
                                <i data-lucide="x-circle" style="width:10px;height:10px;"></i>
                                Sin stock
                            </span>
                        @endif
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="empty">
            <i data-lucide="package-x" style="width:40px;height:40px;"></i>
            <span>No se encontraron productos{{ $buscar ? " para \"$buscar\"" : '' }}.</span>
            @if($buscar)
                <a href="{{ route('catalogo.index') }}" style="color:#a08c78;font-size:13px;">Ver todos</a>
            @endif
        </div>
    @endforelse
</div>

@endsection
