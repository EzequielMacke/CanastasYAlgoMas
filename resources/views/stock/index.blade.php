@extends('layouts.app')

@section('titulo', 'Inventario')

@section('contenido')

<div class="section-header">
    <h1>Inventario</h1>
    <form method="GET" action="{{ route('stock.index') }}" style="display:flex;gap:8px;align-items:center;">
        <div class="input-wrap" style="width:260px;">
            <i data-lucide="search" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
            <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar artículo…" style="padding-left:38px;">
        </div>
        @if($buscar)
            <a href="{{ route('stock.index') }}" class="btn btn-secondary" style="padding:9px 12px;" title="Limpiar">
                <i data-lucide="x" style="width:14px;height:14px;"></i>
            </a>
        @endif
    </form>
</div>

<div class="card" style="padding: 0; overflow: hidden;">

    @if($stocks->isEmpty())
        <div class="empty" style="padding: 60px 0;">
            <i data-lucide="warehouse" style="width:36px;height:36px;"></i>
            <span>{{ $buscar ? 'Sin resultados para "' . $buscar . '".' : 'No hay stock registrado aún.' }}</span>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th style="width:72px;"></th>
                    <th>Artículo</th>
                    <th>Tipo</th>
                    <th style="width:160px;">Stock</th>
                    <th style="width:160px;">Precio de costo</th>
                    <th style="width:100px;">Estado stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                @php
                    $articulo = $stock->articulo;
                    $um       = $articulo->unidadMedida;
                    $costo    = $articulo->precioCosto;
                    $sinStock = $stock->cantidad <= 0;
                    $bajoStock = $stock->cantidad > 0 && $stock->cantidad < 10;
                @endphp
                <tr>
                    <td><span class="badge-id">{{ $articulo->id }}</span></td>
                    <td>
                        @if($articulo->foto)
                            <img src="{{ asset('storage/' . $articulo->foto) }}"
                                 alt="{{ $articulo->nombre }}"
                                 style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e8e0d8;{{ $sinStock ? 'opacity:0.4;' : '' }}">
                        @else
                            <div style="width:48px;height:48px;border-radius:6px;border:1px solid #e8e0d8;background:#f5f0eb;display:flex;align-items:center;justify-content:center;{{ $sinStock ? 'opacity:0.4;' : '' }}">
                                <i data-lucide="image" style="width:18px;height:18px;color:#c4b8ac;"></i>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight:500;{{ $sinStock ? 'color:#a08c78;' : '' }}">
                        {{ $articulo->nombre }}
                    </td>
                    <td style="color:#6b5744;font-size:13px;">{{ $articulo->tipoArticulo->nombre }}</td>
                    <td>
                        <span style="font-size:15px;font-weight:600;color:{{ $sinStock ? '#c0392b' : ($bajoStock ? '#b07d27' : '#27794a') }};">
                            {{ number_format($stock->cantidad, 2) }}
                        </span>
                        <span style="font-size:12px;color:#a08c78;margin-left:3px;">{{ $um->abreviatura }}</span>
                    </td>
                    <td style="font-size:13px;color:#6b5744;">
                        @if($costo)
                            Gs. {{ number_format($costo->costo, 2, ',', '.') }}
                            <span style="color:#a08c78;font-size:11px;">/ {{ $um->abreviatura }}</span>
                        @else
                            <span style="color:#c4b8ac;">Sin datos</span>
                        @endif
                    </td>
                    <td>
                        @if($sinStock)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;background:#fff5f5;color:#c0392b;border:1px solid #f5c6c4;padding:3px 9px;border-radius:10px;">
                                <i data-lucide="circle-x" style="width:11px;height:11px;"></i>
                                Sin stock
                            </span>
                        @elseif($bajoStock)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;background:#fefce8;color:#b07d27;border:1px solid #fde68a;padding:3px 9px;border-radius:10px;">
                                <i data-lucide="triangle-alert" style="width:11px;height:11px;"></i>
                                Bajo
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;background:#f0faf3;color:#27794a;border:1px solid #b8dfc6;padding:3px 9px;border-radius:10px;">
                                <i data-lucide="circle-check" style="width:11px;height:11px;"></i>
                                OK
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="padding:12px 28px;border-top:1px solid #f5f0eb;display:flex;gap:24px;font-size:12px;color:#a08c78;">
            <span><strong style="color:#2c2117;">{{ $stocks->count() }}</strong> artículos</span>
            <span><strong style="color:#c0392b;">{{ $stocks->where('cantidad', '<=', 0)->count() }}</strong> sin stock</span>
            <span><strong style="color:#b07d27;">{{ $stocks->filter(fn($s) => $s->cantidad > 0 && $s->cantidad < 10)->count() }}</strong> con stock bajo</span>
        </div>
    @endif

</div>

@endsection
