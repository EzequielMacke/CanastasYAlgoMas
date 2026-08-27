@extends('layouts.app')

@section('titulo', 'Finanzas')

@section('contenido')

@php
    $tabsInfo = [
        'gastos'   => ['label' => 'Gastos',   'icon' => 'arrow-down-to-line', 'color' => '#c0392b'],
        'ventas'   => ['label' => 'Ventas',   'icon' => 'receipt',            'color' => '#3b5bdb'],
        'ganancia' => ['label' => 'Ganancia', 'icon' => 'trending-up',        'color' => '#27794a'],
    ];

    $tituloPeriodo = match($periodo) {
        'dia'   => \Carbon\Carbon::parse($fecha)->format('d/m/Y'),
        'mes'   => $meses[$mes] . ' ' . $anio,
        default => (string) $anio,
    };
@endphp

<div class="section-header">
    <h1>Finanzas</h1>
</div>

<div class="card" style="padding: 0; overflow: hidden; margin-bottom:20px;">
    <div class="tabs">
        @foreach($tabsInfo as $key => $info)
            <a href="{{ route('finanzas.index', ['tab' => $key, 'periodo' => $periodo, 'fecha' => $fecha, 'mes' => $mes, 'anio' => $anio]) }}"
               class="tab-btn {{ $tab === $key ? 'activo' : '' }}">
                <i data-lucide="{{ $info['icon'] }}" style="width:14px;height:14px;"></i>
                {{ $info['label'] }}
            </a>
        @endforeach
    </div>
</div>

{{-- ── Filtros ── --}}
<div class="card" style="padding:20px 28px;margin-bottom:20px;">
    <form method="GET" action="{{ route('finanzas.index') }}" style="display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;">
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">Ver por</label>
            <select name="periodo" id="sel-periodo" style="padding:9px 12px;">
                <option value="dia"  {{ $periodo === 'dia'  ? 'selected' : '' }}>Día</option>
                <option value="mes"  {{ $periodo === 'mes'  ? 'selected' : '' }}>Mes</option>
                <option value="anio" {{ $periodo === 'anio' ? 'selected' : '' }}>Año</option>
            </select>
        </div>

        <div class="filtro-campo" data-periodos="dia" style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">Fecha</label>
            <input type="date" name="fecha" value="{{ $fecha }}" style="padding:9px 12px;">
        </div>

        <div class="filtro-campo" data-periodos="mes" style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">Mes</label>
            <select name="mes" style="padding:9px 12px;">
                @foreach($meses as $num => $nombre)
                    <option value="{{ $num }}" {{ $mes == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="filtro-campo" data-periodos="mes anio" style="display:flex;flex-direction:column;gap:6px;">
            <label style="font-size:11px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">Año</label>
            <select name="anio" style="padding:9px 12px;width:100px;">
                @for($y = now()->year; $y >= now()->year - 4; $y--)
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

{{-- ── Tarjetas resumen ── --}}
@if($tab === 'ganancia')
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">
        <div class="card" style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Ventas</div>
            <div style="font-size:24px;font-weight:700;color:#2c2117;letter-spacing:-0.02em;">Gs. {{ number_format($filas->sum('ventas'), 0, ',', '.') }}</div>
            <div style="font-size:12px;color:#a08c78;margin-top:4px;">{{ $tituloPeriodo }}</div>
        </div>
        <div class="card" style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Costo de mercadería</div>
            <div style="font-size:24px;font-weight:700;color:#c0392b;letter-spacing:-0.02em;">Gs. {{ number_format($filas->sum('costo'), 0, ',', '.') }}</div>
            <div style="font-size:12px;color:#a08c78;margin-top:4px;">costo actual por artículo</div>
        </div>
        <div class="card" style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Ganancia</div>
            <div style="font-size:24px;font-weight:700;color:{{ $total >= 0 ? '#27794a' : '#c0392b' }};letter-spacing:-0.02em;">
                {{ $total >= 0 ? '' : '-' }}Gs. {{ number_format(abs($total), 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:#a08c78;margin-top:4px;">{{ $cantidad }} {{ $cantidad === 1 ? 'venta' : 'ventas' }}</div>
        </div>
    </div>
@else
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:20px;">
        <div class="card" style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">
                Total {{ strtolower($tabsInfo[$tab]['label']) }}
            </div>
            <div style="font-size:28px;font-weight:700;color:{{ $tabsInfo[$tab]['color'] }};letter-spacing:-0.02em;">
                Gs. {{ number_format($total, 0, ',', '.') }}
            </div>
            <div style="font-size:12px;color:#a08c78;margin-top:4px;">{{ $tituloPeriodo }}</div>
        </div>
        <div class="card" style="padding:20px 24px;">
            <div style="font-size:11px;font-weight:600;color:#a08c78;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">
                {{ $tab === 'gastos' ? 'Ingresos registrados' : 'Ventas registradas' }}
            </div>
            <div style="font-size:28px;font-weight:700;color:#2c2117;letter-spacing:-0.02em;">{{ $cantidad }}</div>
        </div>
    </div>
@endif

{{-- ── Detalle ── --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:18px 24px 14px;border-bottom:1px solid #ede7df;display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:14px;font-weight:600;color:#2c2117;">
            Detalle de {{ strtolower($tabsInfo[$tab]['label']) }} — {{ $tituloPeriodo }}
        </div>
        @if($filas->isNotEmpty())
            <span style="font-size:12px;color:#a08c78;">{{ $cantidad }} {{ $cantidad === 1 ? 'registro' : 'registros' }}</span>
        @endif
    </div>

    @if($filas->isEmpty())
        <div class="empty">
            <i data-lucide="{{ $tabsInfo[$tab]['icon'] }}" style="width:36px;height:36px;"></i>
            <span>No hay {{ strtolower($tabsInfo[$tab]['label']) }} para {{ $tituloPeriodo }}.</span>
        </div>
    @elseif($tab === 'gastos')
        <table>
            <thead>
                <tr>
                    <th style="width:60px;">#</th>
                    <th style="width:100px;">Fecha</th>
                    <th>Artículo</th>
                    <th>Cantidad</th>
                    <th style="text-align:right;">Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filas as $ingreso)
                    <tr>
                        <td><span class="badge-id">{{ $ingreso->id }}</span></td>
                        <td style="color:#6b5744;font-size:13px;">{{ $ingreso->fecha->format('d/m/Y') }}</td>
                        <td style="font-weight:500;">{{ $ingreso->articulo->nombre }}</td>
                        <td style="color:#6b5744;">
                            {{ number_format($ingreso->cantidad, 2) }}
                            <span style="color:#a08c78;font-size:12px;">{{ $ingreso->articulo->unidadMedida->abreviatura }}</span>
                        </td>
                        <td style="text-align:right;font-weight:600;">Gs. {{ number_format($ingreso->precio, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tab === 'ventas')
        <table>
            <thead>
                <tr>
                    <th style="width:80px;"># Venta</th>
                    <th>Vendedor</th>
                    <th>Cliente</th>
                    <th style="width:130px;">Fecha</th>
                    <th style="text-align:right;">Total</th>
                    <th style="width:60px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($filas as $venta)
                    <tr>
                        <td><span class="badge-id">#{{ $venta->numero }}</span></td>
                        <td style="color:#6b5744;font-size:13px;">{{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellido }}</td>
                        <td style="color:#6b5744;font-size:13px;">{{ $venta->cliente_nombre ?: '—' }}</td>
                        <td style="color:#a08c78;font-size:13px;">{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align:right;font-weight:600;">Gs. {{ number_format($venta->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('ventas.show', $venta) }}" class="btn btn-secondary btn-sm">
                                <i data-lucide="eye" style="width:12px;height:12px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:80px;"># Venta</th>
                    <th>Vendedor</th>
                    <th style="width:130px;">Fecha</th>
                    <th style="text-align:right;">Ventas</th>
                    <th style="text-align:right;">Costo</th>
                    <th style="text-align:right;">Ganancia</th>
                    <th style="width:60px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($filas as $fila)
                    <tr>
                        <td><span class="badge-id">#{{ $fila['venta']->numero }}</span></td>
                        <td style="color:#6b5744;font-size:13px;">{{ $fila['venta']->vendedor->nombre }} {{ $fila['venta']->vendedor->apellido }}</td>
                        <td style="color:#a08c78;font-size:13px;">{{ $fila['venta']->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align:right;color:#3b5bdb;">Gs. {{ number_format($fila['ventas'], 0, ',', '.') }}</td>
                        <td style="text-align:right;color:#c0392b;">Gs. {{ number_format($fila['costo'], 0, ',', '.') }}</td>
                        <td style="text-align:right;font-weight:600;color:{{ $fila['ganancia'] >= 0 ? '#27794a' : '#c0392b' }};">
                            {{ $fila['ganancia'] >= 0 ? '' : '-' }}Gs. {{ number_format(abs($fila['ganancia']), 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('ventas.show', $fila['venta']) }}" class="btn btn-secondary btn-sm">
                                <i data-lucide="eye" style="width:12px;height:12px;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($filas->isNotEmpty())
        {{-- Total al pie --}}
        <div style="padding:16px 24px;border-top:2px solid #2c2117;display:flex;justify-content:space-between;align-items:center;background:#fdfaf7;">
            <span style="font-size:12px;font-weight:600;color:#6b5744;text-transform:uppercase;letter-spacing:.05em;">
                Total {{ strtolower($tabsInfo[$tab]['label']) }}
            </span>
            <span style="font-size:20px;font-weight:700;color:{{ $total >= 0 ? '#2c2117' : '#c0392b' }};">
                {{ $total >= 0 ? '' : '-' }}Gs. {{ number_format(abs($total), 0, ',', '.') }}
            </span>
        </div>
    @endif
</div>

<script>
    (function () {
        const selPeriodo = document.getElementById('sel-periodo');
        const campos      = document.querySelectorAll('.filtro-campo');

        function actualizarCampos() {
            campos.forEach(function (campo) {
                const periodos = campo.dataset.periodos.split(' ');
                campo.style.display = periodos.includes(selPeriodo.value) ? 'flex' : 'none';
            });
        }

        selPeriodo.addEventListener('change', actualizarCampos);
        actualizarCampos();
    })();
</script>

@endsection
