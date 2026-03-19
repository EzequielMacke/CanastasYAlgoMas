@extends('layouts.app')

@section('titulo', 'Comisiones')

@section('contenido')

@php
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo',  6 => 'Junio',   7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];
@endphp

<div class="section-header">
    <h1>Comisiones</h1>
</div>

{{-- ── Filtros ── --}}
<div class="card" style="padding:20px 28px;margin-bottom:20px;">
    <form method="GET" action="{{ route('comisiones.index') }}" style="display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;">

        <div style="display:flex;flex-direction:column;gap:6px;min-width:200px;">
            <label style="font-size:11px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">Vendedor</label>
            <select name="vendedor_id" style="padding:9px 12px;">
                <option value="">Seleccionar vendedor…</option>
                @foreach($vendedores as $v)
                    <option value="{{ $v->id }}" {{ $vendedorId == $v->id ? 'selected' : '' }}>
                        {{ $v->nombre }} {{ $v->apellido }}
                        @if($v->estado_id !== 1) (inactivo) @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">Mes</label>
            <select name="mes" style="padding:9px 12px;">
                @foreach($meses as $num => $nombre)
                    <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">Año</label>
            <select name="anio" style="padding:9px 12px;width:100px;">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $anio == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i data-lucide="search" style="width:14px;height:14px;"></i>
            Consultar
        </button>

    </form>
</div>

@if($vendedor)

    {{-- ── Tarjetas resumen ── --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">

        <div class="card" style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Vendedor</div>
            <div style="font-size:18px;font-weight:700;color:#2c2117;">{{ $vendedor->nombre }} {{ $vendedor->apellido }}</div>
            <div style="font-size:12px;color:#a08c78;margin-top:4px;">{{ $meses[$mes] }} {{ $anio }}</div>
        </div>

        <div class="card" style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Ventas realizadas</div>
            <div style="font-size:32px;font-weight:700;color:#2c2117;letter-spacing:-0.02em;">{{ $cantidadVentas }}</div>
            <div style="font-size:12px;color:#a08c78;margin-top:4px;">ventas activas</div>
        </div>

        <div class="card" style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Total vendido</div>
            <div style="font-size:26px;font-weight:700;color:#2c2117;letter-spacing:-0.02em;">Gs. {{ number_format($totalVendido, 0, ',', '.') }}</div>
            <div style="font-size:12px;color:#a08c78;margin-top:4px;">en ventas activas</div>
        </div>

    </div>

    {{-- ── Tabla de ventas ── --}}
    <div class="card" style="padding:0;overflow:hidden;">

        <div style="padding:18px 24px 14px;border-bottom:1px solid #ede7df;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:14px;font-weight:600;color:#2c2117;">
                Detalle de ventas — {{ $meses[$mes] }} {{ $anio }}
            </div>
            @if($ventas->isNotEmpty())
                <span style="font-size:12px;color:#a08c78;">{{ $cantidadVentas }} {{ $cantidadVentas === 1 ? 'venta' : 'ventas' }}</span>
            @endif
        </div>

        @if($ventas->isEmpty())
            <div class="empty">
                <i data-lucide="receipt" style="width:36px;height:36px;"></i>
                <span>No hay ventas activas para {{ $meses[$mes] }} {{ $anio }}.</span>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th style="width:80px;"># Venta</th>
                        <th>Cliente</th>
                        <th style="width:60px;text-align:center;">Ítems</th>
                        <th>Artículos</th>
                        <th style="width:110px;">Fecha</th>
                        <th style="text-align:right;">Total</th>
                        <th style="width:60px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ventas as $venta)
                        <tr>
                            <td>
                                <span class="badge-id">#{{ $venta->numero }}</span>
                            </td>
                            <td style="color:#6b5744;font-size:13px;">
                                {{ $venta->cliente_nombre ?: '—' }}
                            </td>
                            <td style="text-align:center;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;background:#f5f0eb;color:#6b5744;border-radius:10px;font-size:11px;font-weight:600;padding:2px 8px;">
                                    {{ $venta->detalles->count() }}
                                </span>
                            </td>
                            <td style="font-size:12px;color:#6b5744;max-width:240px;">
                                {{ $venta->detalles->map(fn($d) => $d->articulo->nombre)->join(', ') }}
                            </td>
                            <td style="color:#a08c78;font-size:13px;">
                                {{ $venta->created_at->format('d/m/Y') }}
                            </td>
                            <td style="text-align:right;font-weight:600;">
                                Gs. {{ number_format($venta->total, 0, ',', '.') }}
                            </td>
                            <td>
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="eye" style="width:12px;height:12px;"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Total al pie --}}
            <div style="padding:16px 24px;border-top:2px solid #2c2117;display:flex;justify-content:space-between;align-items:center;background:#fdfaf7;">
                <span style="font-size:12px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">Total del mes</span>
                <span style="font-size:20px;font-weight:700;color:#2c2117;">Gs. {{ number_format($totalVendido, 0, ',', '.') }}</span>
            </div>
        @endif

    </div>

@else
    <div class="card">
        <div class="empty" style="padding:60px 0;">
            <i data-lucide="users" style="width:40px;height:40px;"></i>
            <span>Seleccioná un vendedor para ver sus comisiones.</span>
        </div>
    </div>
@endif

@endsection
