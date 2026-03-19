@extends('layouts.app')

@section('titulo', 'Recetas')

@section('contenido')

<div class="section-header">
    <h1>Recetas</h1>
    <a href="{{ route('recetas.create') }}" class="btn btn-primary">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Nueva receta
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <div class="tabs">
        <a href="{{ route('recetas.index', ['tab' => 'activas']) }}"
           class="tab-btn {{ $tab === 'activas' ? 'activo' : '' }}">
            <i data-lucide="circle-check" style="width:14px;height:14px;"></i>
            Activas
            <span class="tab-count">{{ $activas->count() }}</span>
        </a>
        <a href="{{ route('recetas.index', ['tab' => 'inactivas']) }}"
           class="tab-btn {{ $tab === 'inactivas' ? 'activo' : '' }}">
            <i data-lucide="circle-pause" style="width:14px;height:14px;"></i>
            Inactivas
            <span class="tab-count">{{ $inactivas->count() }}</span>
        </a>
    </div>

    <div class="tab-content">

        @if($tab === 'activas')
            @if($activas->isEmpty())
                <div class="empty">
                    <i data-lucide="chef-hat" style="width:36px;height:36px;"></i>
                    <span>No hay recetas activas.</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Artículo (producción)</th>
                            <th>Tipo</th>
                            <th style="width:100px;">Ingredientes</th>
                            <th style="width:200px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activas as $receta)
                        <tr>
                            <td><span class="badge-id">{{ $receta->id }}</span></td>
                            <td style="font-weight:500;">{{ $receta->articulo->nombre }}</td>
                            <td style="color:#6b5744;">{{ $receta->articulo->tipoArticulo->nombre }}</td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#6b5744;background:#f5f0eb;padding:3px 9px;border-radius:10px;font-weight:600;">
                                    <i data-lucide="list" style="width:12px;height:12px;"></i>
                                    {{ $receta->items->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="td-acciones">
                                    <a href="{{ route('recetas.edit', $receta) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('recetas.destroy', $receta) }}"
                                          onsubmit="return confirm('¿Desactivar receta de {{ $receta->articulo->nombre }}?')">
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
            @if($inactivas->isEmpty())
                <div class="empty">
                    <i data-lucide="chef-hat" style="width:36px;height:36px;"></i>
                    <span>No hay recetas inactivas.</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Artículo (producción)</th>
                            <th>Tipo</th>
                            <th style="width:100px;">Ingredientes</th>
                            <th style="width:200px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inactivas as $receta)
                        <tr>
                            <td><span class="badge-id">{{ $receta->id }}</span></td>
                            <td style="font-weight:500;color:#a08c78;">{{ $receta->articulo->nombre }}</td>
                            <td style="color:#c4b8ac;">{{ $receta->articulo->tipoArticulo->nombre }}</td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;color:#c4b8ac;background:#f5f0eb;padding:3px 9px;border-radius:10px;font-weight:600;">
                                    <i data-lucide="list" style="width:12px;height:12px;"></i>
                                    {{ $receta->items->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="td-acciones">
                                    <a href="{{ route('recetas.edit', $receta) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('recetas.reactivar', $receta) }}"
                                          onsubmit="return confirm('¿Reactivar receta de {{ $receta->articulo->nombre }}?')">
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
