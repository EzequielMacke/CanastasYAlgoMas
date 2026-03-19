@extends('layouts.app')

@section('titulo', 'Ventas')

@section('contenido')

<div class="section-header">
    <h1>Ventas</h1>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Nueva venta
    </a>
</div>

<div class="card" style="padding:0;overflow:hidden;">
    <div class="tabs">
        <a href="{{ route('ventas.index', ['tab' => 'activas']) }}"
           class="tab-btn {{ $tab === 'activas' ? 'activo' : '' }}">
            <i data-lucide="circle-check" style="width:14px;height:14px;"></i>
            Activas
            <span class="tab-count">{{ $activas->count() }}</span>
        </a>
        <a href="{{ route('ventas.index', ['tab' => 'anuladas']) }}"
           class="tab-btn {{ $tab === 'anuladas' ? 'activo' : '' }}">
            <i data-lucide="ban" style="width:14px;height:14px;"></i>
            Anuladas
            <span class="tab-count">{{ $anuladas->count() }}</span>
        </a>
    </div>

    <div class="tab-content">

        @php $lista = $tab === 'activas' ? $activas : $anuladas; @endphp

        @if($lista->isEmpty())
            <div class="empty">
                <i data-lucide="receipt" style="width:36px;height:36px;"></i>
                <span>No hay ventas {{ $tab }}.</span>
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
                        <th style="width:110px;">Fecha</th>
                        <th style="width:220px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lista as $venta)
                    <tr>
                        <td>
                            <span class="badge-id">#{{ $venta->numero }}</span>
                        </td>
                        <td style="font-weight:500;">
                            {{ $venta->vendedor->nombre }} {{ $venta->vendedor->apellido }}
                        </td>
                        <td style="color:#6b5744;font-size:13px;">
                            {{ $venta->cliente_nombre ?: '—' }}
                        </td>
                        <td style="text-align:center;">
                            <span style="display:inline-flex;align-items:center;justify-content:center;background:#f5f0eb;color:#6b5744;border-radius:10px;font-size:11px;font-weight:600;padding:2px 8px;">
                                {{ $venta->detalles->count() }}
                            </span>
                        </td>
                        <td style="text-align:right;font-weight:600;">
                            Gs. {{ number_format($venta->total, 0, ',', '.') }}
                        </td>
                        <td style="color:#a08c78;font-size:13px;">
                            {{ $venta->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="td-acciones">
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-secondary btn-sm">
                                    <i data-lucide="eye" style="width:13px;height:13px;"></i>
                                    Ver
                                </a>
                                @if(auth()->user()->nick === 'admin')
                                    <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    @if($tab === 'activas')
                                        <form method="POST" action="{{ route('ventas.destroy', $venta) }}"
                                              onsubmit="return confirm('¿Anular venta #{{ $venta->numero }}? Se devolverá el stock.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i data-lucide="ban" style="width:13px;height:13px;"></i>
                                                Anular
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('ventas.reactivar', $venta) }}"
                                              onsubmit="return confirm('¿Reactivar venta #{{ $venta->numero }}?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i data-lucide="circle-check" style="width:13px;height:13px;"></i>
                                                Reactivar
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>

@endsection
