@extends('layouts.app')

@section('titulo', 'Apertura #' . $aperturaCaja->id)

@section('contenido')

<div class="section-header">
    <div style="display:flex;align-items:center;gap:10px;">
        <a href="{{ route('apertura-caja.index') }}" class="btn btn-secondary btn-sm">
            <i data-lucide="arrow-left" style="width:13px;height:13px;"></i>
            Volver
        </a>
        <h1>Apertura #{{ $aperturaCaja->id }}</h1>
    </div>
    @if($aperturaCaja->estaAbierta())
        <span style="display:inline-flex;align-items:center;gap:6px;background:#edf7f1;color:#2e7d5a;border-radius:20px;font-size:12px;font-weight:600;padding:5px 14px;">
            <i data-lucide="circle" style="width:8px;height:8px;fill:#4caf7d;stroke:none;"></i>
            Caja abierta
        </span>
    @else
        <span style="display:inline-flex;align-items:center;gap:6px;background:#f5f0eb;color:#a08c78;border-radius:20px;font-size:12px;font-weight:600;padding:5px 14px;">
            <i data-lucide="circle" style="width:8px;height:8px;fill:#a08c78;stroke:none;"></i>
            Caja cerrada
        </span>
    @endif
</div>

{{-- Info de apertura --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:24px;">
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:18px 20px;">
        <div style="font-size:11px;font-weight:500;color:#a08c78;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Fecha</div>
        <div style="font-size:16px;font-weight:600;color:#2c2117;">{{ $aperturaCaja->fecha->format('d/m/Y') }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:18px 20px;">
        <div style="font-size:11px;font-weight:500;color:#a08c78;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Hora apertura</div>
        <div style="font-size:16px;font-weight:600;color:#2c2117;">{{ $aperturaCaja->abierto_at->format('H:i') }}</div>
    </div>
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:18px 20px;">
        <div style="font-size:11px;font-weight:500;color:#a08c78;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Hora cierre</div>
        <div style="font-size:16px;font-weight:600;color:#2c2117;">
            {{ $aperturaCaja->cerrado_at ? $aperturaCaja->cerrado_at->format('H:i') : '—' }}
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:18px 20px;">
        <div style="font-size:11px;font-weight:500;color:#a08c78;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Total ventas</div>
        <div style="font-size:16px;font-weight:600;color:#2c2117;">
            Gs. {{ number_format($totalVentas, 0, ',', '.') }}
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:18px 20px;">
        <div style="font-size:11px;font-weight:500;color:#a08c78;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Total gasto</div>
        <div style="font-size:16px;font-weight:600;color:#c0392b;">
            Gs. {{ number_format($totalGasto, 0, ',', '.') }}
        </div>
    </div>
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:18px 20px;border-left:4px solid {{ $totalGanancia >= 0 ? '#4caf7d' : '#c0392b' }};">
        <div style="font-size:11px;font-weight:500;color:#a08c78;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Ganancia</div>
        <div style="font-size:16px;font-weight:600;color:{{ $totalGanancia >= 0 ? '#2e7d5a' : '#c0392b' }};">
            Gs. {{ number_format($totalGanancia, 0, ',', '.') }}
        </div>
    </div>
</div>

@if($aperturaCaja->observaciones)
    <div style="background:#fff;border:1px solid #e8e0d8;border-radius:10px;padding:16px 20px;margin-bottom:24px;">
        <div style="font-size:11px;font-weight:500;color:#a08c78;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Observaciones</div>
        <p style="font-size:13px;color:#2c2117;">{{ $aperturaCaja->observaciones }}</p>
    </div>
@endif

{{-- Ventas de esta apertura --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f0ebe4;display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:8px;">
            <i data-lucide="receipt" style="width:16px;height:16px;color:#a08c78;"></i>
            <span style="font-weight:600;font-size:14px;">Ventas de esta apertura</span>
            <span style="background:#f5f0eb;color:#6b5744;border-radius:10px;font-size:11px;font-weight:600;padding:2px 8px;">
                {{ $ventas->count() }}
            </span>
        </div>
    </div>

    @if($ventas->isEmpty())
        <div class="empty">
            <i data-lucide="receipt" style="width:36px;height:36px;"></i>
            <span>No hay ventas registradas en esta apertura.</span>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:70px;"># Venta</th>
                    <th>Vendedor</th>
                    <th>Cliente</th>
                    <th style="width:60px;text-align:center;">Ítems</th>
                    <th style="text-align:right;">Total</th>
                    <th style="width:130px;">Hora</th>
                    <th style="width:90px;">Estado</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    <td><span class="badge-id">#{{ $venta->numero }}</span></td>
                    <td style="font-weight:500;">{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellido }}</td>
                    <td style="color:#6b5744;font-size:13px;">{{ $venta->cliente_nombre ?: '—' }}</td>
                    <td style="text-align:center;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;background:#f5f0eb;color:#6b5744;border-radius:10px;font-size:11px;font-weight:600;padding:2px 8px;">
                            {{ $venta->detalles->count() }}
                        </span>
                    </td>
                    <td style="text-align:right;font-weight:600;">Gs. {{ number_format($venta->total, 0, ',', '.') }}</td>
                    <td style="color:#a08c78;font-size:13px;">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($venta->estado_id === 1)
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#edf7f1;color:#2e7d5a;border-radius:20px;font-size:11px;font-weight:600;padding:2px 9px;">
                                <i data-lucide="circle" style="width:6px;height:6px;fill:#4caf7d;stroke:none;"></i>
                                Activa
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#fff0ee;color:#c0392b;border-radius:20px;font-size:11px;font-weight:600;padding:2px 9px;">
                                <i data-lucide="circle" style="width:6px;height:6px;fill:#c0392b;stroke:none;"></i>
                                Anulada
                            </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ventas.show', $venta) }}" class="btn btn-secondary btn-sm">
                            <i data-lucide="eye" style="width:13px;height:13px;"></i>
                            Ver
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
