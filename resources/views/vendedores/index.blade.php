@extends('layouts.app')

@section('titulo', 'Vendedores')

@section('contenido')

<div class="section-header">
    <h1>Vendedores</h1>
    <a href="{{ route('vendedores.create') }}" class="btn btn-primary">
        <i data-lucide="user-plus" style="width:15px;height:15px;"></i>
        Nuevo vendedor
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <div class="tabs">
        <a href="{{ route('vendedores.index', ['tab' => 'activos']) }}"
           class="tab-btn {{ $tab === 'activos' ? 'activo' : '' }}">
            <i data-lucide="circle-check" style="width:14px;height:14px;"></i>
            Activos
            <span class="tab-count">{{ $activos->count() }}</span>
        </a>
        <a href="{{ route('vendedores.index', ['tab' => 'inactivos']) }}"
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
                    <i data-lucide="users" style="width:36px;height:36px;"></i>
                    <span>No hay vendedores activos.</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Apellido</th>
                            <th>Nombre</th>
                            <th style="width:180px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activos as $vendedor)
                        <tr>
                            <td><span class="badge-id">{{ $vendedor->id }}</span></td>
                            <td style="font-weight:500;">{{ $vendedor->apellido }}</td>
                            <td style="color:#6b5744;">{{ $vendedor->nombre }}</td>
                            <td>
                                <div class="td-acciones">
                                    <a href="{{ route('vendedores.edit', $vendedor) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('vendedores.destroy', $vendedor) }}"
                                          onsubmit="return confirm('¿Desactivar a {{ $vendedor->nombre }} {{ $vendedor->apellido }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i data-lucide="user-x" style="width:13px;height:13px;"></i>
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
                    <i data-lucide="user-check" style="width:36px;height:36px;"></i>
                    <span>No hay vendedores inactivos.</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Apellido</th>
                            <th>Nombre</th>
                            <th style="width:180px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inactivos as $vendedor)
                        <tr>
                            <td><span class="badge-id">{{ $vendedor->id }}</span></td>
                            <td style="font-weight:500;color:#a08c78;">{{ $vendedor->apellido }}</td>
                            <td style="color:#c4b8ac;">{{ $vendedor->nombre }}</td>
                            <td>
                                <div class="td-acciones">
                                    <a href="{{ route('vendedores.edit', $vendedor) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('vendedores.reactivar', $vendedor) }}"
                                          onsubmit="return confirm('¿Reactivar a {{ $vendedor->nombre }} {{ $vendedor->apellido }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i data-lucide="user-check" style="width:13px;height:13px;"></i>
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
