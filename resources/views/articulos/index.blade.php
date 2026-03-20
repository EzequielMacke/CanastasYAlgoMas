@extends('layouts.app')

@section('titulo', 'Artículos')

@section('contenido')

<div class="section-header">
    <h1>Artículos</h1>
    <a href="{{ route('articulos.create') }}" class="btn btn-primary">
        <i data-lucide="package-plus" style="width:15px;height:15px;"></i>
        Nuevo artículo
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <div class="tabs">
        <a href="{{ route('articulos.index', ['tab' => 'activos']) }}"
           class="tab-btn {{ $tab === 'activos' ? 'activo' : '' }}">
            <i data-lucide="circle-check" style="width:14px;height:14px;"></i>
            Activos
            <span class="tab-count">{{ $activos->count() }}</span>
        </a>
        <a href="{{ route('articulos.index', ['tab' => 'inactivos']) }}"
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
                    <i data-lucide="package" style="width:36px;height:36px;"></i>
                    <span>No hay artículos activos.</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th style="width:72px;"></th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Unidad</th>
                            <th style="width:110px;text-align:center;">Catálogo</th>
                            <th style="width:260px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activos as $articulo)
                        <tr>
                            <td><span class="badge-id">{{ $articulo->id }}</span></td>
                            <td>
                                @if($articulo->foto)
                                    <img src="{{ asset('storage/' . $articulo->foto) }}"
                                         alt="{{ $articulo->nombre }}"
                                         style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e8e0d8;">
                                @else
                                    <div style="width:48px;height:48px;border-radius:6px;border:1px solid #e8e0d8;background:#f5f0eb;display:flex;align-items:center;justify-content:center;">
                                        <i data-lucide="image" style="width:18px;height:18px;color:#c4b8ac;"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight:500;">{{ $articulo->nombre }}</td>
                            <td style="color:#6b5744;">{{ $articulo->tipoArticulo->nombre }}</td>
                            <td style="color:#a08c78;font-size:13px;">{{ $articulo->unidadMedida->abreviatura }}</td>
                            <td style="text-align:center;">
                                <form method="POST" action="{{ route('articulos.toggle-catalogo', $articulo) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="{{ $articulo->visible_catalogo ? 'Ocultar del catálogo' : 'Mostrar en catálogo' }}"
                                            style="background:none;border:none;cursor:pointer;padding:4px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;transition:opacity 0.15s;"
                                            onmouseenter="this.style.opacity='.7'" onmouseleave="this.style.opacity='1'">
                                        @if($articulo->visible_catalogo)
                                            <i data-lucide="eye" style="width:18px;height:18px;color:#4caf7d;"></i>
                                        @else
                                            <i data-lucide="eye-off" style="width:18px;height:18px;color:#c4b8ac;"></i>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="td-acciones">
                                    @if($articulo->esProduccion())
                                        @if($articulo->receta)
                                            <a href="{{ route('recetas.edit', $articulo->receta) }}" class="btn btn-sm" style="background:#f0f4ff;color:#3b5bdb;border:1px solid #c5d0fa;">
                                                <i data-lucide="chef-hat" style="width:13px;height:13px;"></i>
                                                Ver receta
                                            </a>
                                        @else
                                            <a href="{{ route('recetas.create', ['articulo_id' => $articulo->id]) }}" class="btn btn-sm" style="background:#f0faf3;color:#27794a;border:1px solid #b8dfc6;">
                                                <i data-lucide="chef-hat" style="width:13px;height:13px;"></i>
                                                Agregar receta
                                            </a>
                                        @endif
                                    @endif
                                    <a href="{{ route('articulos.edit', $articulo) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('articulos.destroy', $articulo) }}"
                                          onsubmit="return confirm('¿Desactivar {{ $articulo->nombre }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i data-lucide="package-x" style="width:13px;height:13px;"></i>
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
                    <i data-lucide="package-check" style="width:36px;height:36px;"></i>
                    <span>No hay artículos inactivos.</span>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th style="width:72px;"></th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Unidad</th>
                            <th style="width:260px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inactivos as $articulo)
                        <tr>
                            <td><span class="badge-id">{{ $articulo->id }}</span></td>
                            <td>
                                @if($articulo->foto)
                                    <img src="{{ asset('storage/' . $articulo->foto) }}"
                                         alt="{{ $articulo->nombre }}"
                                         style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e8e0d8;opacity:0.5;">
                                @else
                                    <div style="width:48px;height:48px;border-radius:6px;border:1px solid #e8e0d8;background:#f5f0eb;display:flex;align-items:center;justify-content:center;opacity:0.5;">
                                        <i data-lucide="image" style="width:18px;height:18px;color:#c4b8ac;"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight:500;color:#a08c78;">{{ $articulo->nombre }}</td>
                            <td style="color:#c4b8ac;">{{ $articulo->tipoArticulo->nombre }}</td>
                            <td style="color:#c4b8ac;font-size:13px;">{{ $articulo->unidadMedida->abreviatura }}</td>
                            <td>
                                <div class="td-acciones">
                                    @if($articulo->esProduccion() && $articulo->receta)
                                        <a href="{{ route('recetas.edit', $articulo->receta) }}" class="btn btn-sm" style="background:#f0f4ff;color:#3b5bdb;border:1px solid #c5d0fa;opacity:0.8;">
                                            <i data-lucide="chef-hat" style="width:13px;height:13px;"></i>
                                            Ver receta
                                        </a>
                                    @endif
                                    <a href="{{ route('articulos.edit', $articulo) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('articulos.reactivar', $articulo) }}"
                                          onsubmit="return confirm('¿Reactivar {{ $articulo->nombre }}?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i data-lucide="package-check" style="width:13px;height:13px;"></i>
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
