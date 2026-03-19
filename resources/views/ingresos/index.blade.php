@extends('layouts.app')

@section('titulo', 'Ingresos')

@section('contenido')

<div class="section-header">
    <h1>Ingresos</h1>
    <a href="{{ route('ingresos.create') }}" class="btn btn-primary">
        <i data-lucide="arrow-down-to-line" style="width:15px;height:15px;"></i>
        Nuevo ingreso
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <div class="tabs">
        <a href="{{ route('ingresos.index', ['tab' => 'activos']) }}"
           class="tab-btn {{ $tab === 'activos' ? 'activo' : '' }}">
            <i data-lucide="circle-check" style="width:14px;height:14px;"></i>
            Activos
            <span class="tab-count">{{ $activos->count() }}</span>
        </a>
        <a href="{{ route('ingresos.index', ['tab' => 'inactivos']) }}"
           class="tab-btn {{ $tab === 'inactivos' ? 'activo' : '' }}">
            <i data-lucide="circle-pause" style="width:14px;height:14px;"></i>
            Inactivos
            <span class="tab-count">{{ $inactivos->count() }}</span>
        </a>
    </div>

    <div class="tab-content">

        @if($tab === 'activos')
            @if($activos->isEmpty())
                <div class="empty">
                    <i data-lucide="arrow-down-to-line" style="width:36px;height:36px;"></i>
                    <span>No hay ingresos activos.</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th style="width:100px;">Fecha</th>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio total</th>
                            <th>Costo unit.</th>
                            <th style="width:200px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activos as $ingreso)
                        <tr>
                            <td><span class="badge-id">{{ $ingreso->id }}</span></td>
                            <td style="color:#6b5744;font-size:13px;">{{ $ingreso->fecha->format('d/m/Y') }}</td>
                            <td style="font-weight:500;">{{ $ingreso->articulo->nombre }}</td>
                            <td style="color:#6b5744;">
                                {{ number_format($ingreso->cantidad, 2) }}
                                <span style="color:#a08c78;font-size:12px;">{{ $ingreso->articulo->unidadMedida->abreviatura }}</span>
                            </td>
                            <td style="font-weight:500;">Gs. {{ number_format($ingreso->precio, 0, ',', '.') }}</td>
                            <td style="font-size:13px;color:#6b5744;">
                                Gs. {{ number_format($ingreso->precio_costo, 2, ',', '.') }}
                                <span style="color:#a08c78;font-size:11px;">/ {{ $ingreso->articulo->unidadMedida->abreviatura }}</span>
                            </td>
                            <td>
                                <div class="td-acciones">
                                    <a href="{{ route('ingresos.edit', $ingreso) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('ingresos.destroy', $ingreso) }}"
                                          onsubmit="return confirm('¿Desactivar este ingreso? Se restará del stock.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i data-lucide="ban" style="width:13px;height:13px;"></i>
                                            Desactivar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        @else
            @if($inactivos->isEmpty())
                <div class="empty">
                    <i data-lucide="arrow-down-to-line" style="width:36px;height:36px;"></i>
                    <span>No hay ingresos inactivos.</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th style="width:100px;">Fecha</th>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio total</th>
                            <th>Costo unit.</th>
                            <th style="width:200px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inactivos as $ingreso)
                        <tr>
                            <td><span class="badge-id">{{ $ingreso->id }}</span></td>
                            <td style="color:#c4b8ac;font-size:13px;">{{ $ingreso->fecha->format('d/m/Y') }}</td>
                            <td style="font-weight:500;color:#a08c78;">{{ $ingreso->articulo->nombre }}</td>
                            <td style="color:#c4b8ac;">
                                {{ number_format($ingreso->cantidad, 2) }}
                                <span style="font-size:12px;">{{ $ingreso->articulo->unidadMedida->abreviatura }}</span>
                            </td>
                            <td style="color:#a08c78;">Gs. {{ number_format($ingreso->precio, 0, ',', '.') }}</td>
                            <td style="color:#c4b8ac;font-size:13px;">
                                Gs. {{ number_format($ingreso->precio_costo, 2, ',', '.') }}
                                <span style="font-size:11px;">/ {{ $ingreso->articulo->unidadMedida->abreviatura }}</span>
                            </td>
                            <td>
                                <div class="td-acciones">
                                    <a href="{{ route('ingresos.edit', $ingreso) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('ingresos.reactivar', $ingreso) }}"
                                          onsubmit="return confirm('¿Reactivar este ingreso? Se sumará al stock.')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i data-lucide="circle-check" style="width:13px;height:13px;"></i>
                                            Reactivar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif

    </div>
</div>

@endsection
