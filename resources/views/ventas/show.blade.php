@extends('layouts.app')

@section('titulo', 'Venta #' . $venta->numero)

@push('styles')
<style>
    .recibo-wrap {
        max-width: 620px;
        margin: 0 auto;
    }

    .recibo-acciones {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        justify-content: flex-end;
    }

    .recibo {
        background: #fff;
        border: 1px solid #e8e0d8;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    /* ── Encabezado ── */
    .recibo-head {
        background: #2c2117;
        padding: 28px 36px 24px;
        color: #f5f0eb;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .recibo-brand {
        font-size: 17px;
        font-weight: 600;
        letter-spacing: 0.01em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .recibo-brand svg { opacity: 0.8; }

    .recibo-head-right {
        text-align: right;
    }

    .recibo-numero {
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: #fff;
    }

    .recibo-fecha {
        font-size: 13px;
        color: #c4b8ac;
        margin-top: 3px;
    }

    /* ── Badge estado ── */
    .recibo-estado {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        margin-top: 6px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .recibo-estado.activa  { background: rgba(87,186,101,0.18); color: #57ba65; }
    .recibo-estado.anulada { background: rgba(192,57,43,0.18); color: #e07060; }

    /* ── Info vendedor/cliente ── */
    .recibo-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        border-bottom: 1px solid #ede7df;
    }

    .recibo-info-bloque {
        padding: 18px 36px;
    }

    .recibo-info-bloque + .recibo-info-bloque {
        border-left: 1px solid #ede7df;
    }

    .recibo-info-label {
        font-size: 11px;
        font-weight: 600;
        color: #a08c78;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 5px;
    }

    .recibo-info-valor {
        font-size: 14px;
        font-weight: 500;
        color: #2c2117;
    }

    .recibo-info-sub {
        font-size: 12px;
        color: #a08c78;
        margin-top: 2px;
    }

    /* ── Tabla ítems ── */
    .recibo-tabla {
        padding: 0 36px;
    }

    .recibo-tabla table {
        width: 100%;
        border-collapse: collapse;
    }

    .recibo-tabla th {
        font-size: 11px;
        font-weight: 600;
        color: #a08c78;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 14px 0 10px;
        border-bottom: 1px solid #ede7df;
        text-align: left;
    }

    .recibo-tabla th:last-child { text-align: right; }
    .recibo-tabla th:nth-child(2),
    .recibo-tabla th:nth-child(3) { text-align: right; }

    .recibo-tabla td {
        padding: 11px 0;
        border-bottom: 1px solid #f5f0eb;
        font-size: 14px;
        color: #2c2117;
        vertical-align: middle;
    }

    .recibo-tabla td:nth-child(2),
    .recibo-tabla td:nth-child(3),
    .recibo-tabla td:last-child { text-align: right; }

    .recibo-tabla tr:last-child td { border-bottom: none; }

    .recibo-tabla .item-nombre { font-weight: 500; }
    .recibo-tabla .item-tipo   { font-size: 11px; color: #a08c78; margin-top: 2px; }

    /* ── Total ── */
    .recibo-total {
        padding: 16px 36px 20px;
        border-top: 2px solid #2c2117;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .recibo-total-label {
        font-size: 13px;
        font-weight: 600;
        color: #6b5744;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .recibo-total-valor {
        font-size: 22px;
        font-weight: 700;
        color: #2c2117;
        letter-spacing: -0.01em;
    }

    /* ── Pie ── */
    .recibo-pie {
        background: #fdfaf7;
        border-top: 1px solid #ede7df;
        padding: 12px 36px;
        font-size: 12px;
        color: #c4b8ac;
        text-align: center;
    }

    /* ── Print ── */
    @media print {
        nav, .recibo-acciones, .recibo-estado { display: none !important; }
        main { margin: 0 !important; padding: 0 !important; max-width: 100% !important; }
        .recibo-wrap { max-width: 100%; }
        .recibo {
            border: none;
            box-shadow: none;
            border-radius: 0;
        }
        .recibo-head { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        body { background: #fff !important; }
    }
</style>
@endpush

@section('contenido')

<div class="recibo-wrap">

    <div class="recibo-acciones no-print">
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
            Volver al menú
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i data-lucide="printer" style="width:14px;height:14px;"></i>
            Imprimir
        </button>
    </div>

    <div class="recibo">

        {{-- Encabezado --}}
        <div class="recibo-head">
            <div>
                <div class="recibo-brand">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    Canastas y Algo Más
                </div>
                <div style="margin-top:10px;">
                    @if($venta->estado_id === 1)
                        <span class="recibo-estado activa">
                            <i data-lucide="circle-check" style="width:11px;height:11px;"></i>
                            Activa
                        </span>
                    @else
                        <span class="recibo-estado anulada">
                            <i data-lucide="ban" style="width:11px;height:11px;"></i>
                            Anulada
                        </span>
                    @endif
                </div>
            </div>
            <div class="recibo-head-right">
                <div class="recibo-numero">Venta #{{ $venta->numero }}</div>
                <div class="recibo-fecha">{{ $venta->created_at->format('d/m/Y  H:i') }}</div>
            </div>
        </div>

        {{-- Info vendedor / cliente --}}
        <div class="recibo-info">
            <div class="recibo-info-bloque">
                <div class="recibo-info-label">Vendedor</div>
                <div class="recibo-info-valor">{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellido }}</div>
            </div>
            <div class="recibo-info-bloque">
                <div class="recibo-info-label">Cliente</div>
                <div class="recibo-info-valor">{{ $venta->cliente_nombre ?: '—' }}</div>
            </div>
        </div>

        {{-- Tabla ítems --}}
        <div class="recibo-tabla">
            <table>
                <thead>
                    <tr>
                        <th>Artículo / Servicio</th>
                        <th>Cantidad</th>
                        <th>Precio unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->detalles as $detalle)
                        <tr>
                            <td>
                                <div class="item-nombre">{{ $detalle->articulo->nombre }}</div>
                                <div class="item-tipo">{{ $detalle->articulo->tipoArticulo->nombre ?? '' }}</div>
                            </td>
                            <td>
                                {{ number_format($detalle->cantidad, 2, ',', '.') }}
                                <span style="font-size:12px;color:#a08c78;">
                                    {{ $detalle->articulo->unidadMedida?->abreviatura ?? '' }}
                                </span>
                            </td>
                            <td>Gs. {{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                            <td style="font-weight:600;">Gs. {{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Total --}}
        <div class="recibo-total">
            <span class="recibo-total-label">Total</span>
            <span class="recibo-total-valor">Gs. {{ number_format($venta->total, 0, ',', '.') }}</span>
        </div>

        {{-- Pie --}}
        <div class="recibo-pie">
            {{ $venta->detalles->count() }} {{ $venta->detalles->count() === 1 ? 'ítem' : 'ítems' }}
            &nbsp;·&nbsp; Registrado el {{ $venta->created_at->format('d/m/Y \a \l\a\s H:i') }}
        </div>

    </div>
</div>

@endsection
