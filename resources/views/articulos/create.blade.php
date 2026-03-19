@extends('layouts.app')

@section('titulo', 'Nuevo Artículo')

@section('contenido')

<div class="section-header">
    <div>
        <h1>Nuevo artículo</h1>
        <p style="font-size:13px;color:#a08c78;margin-top:4px;">Completá los datos para registrar un artículo.</p>
    </div>
    <a href="{{ route('articulos.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
        Volver
    </a>
</div>

<div class="card" style="max-width: 560px;">
    <form method="POST" action="{{ route('articulos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <div class="input-wrap">
                <i data-lucide="package" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus placeholder="Ej: Arroz integral">
            </div>
            @error('nombre') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Descripción opcional del artículo...">{{ old('descripcion') }}</textarea>
            @error('descripcion') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="tipo_articulo_id">Tipo de artículo</label>
            <select id="tipo_articulo_id" name="tipo_articulo_id" required>
                <option value="">— Seleccioná un tipo —</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}"
                            data-nombre="{{ strtolower($tipo->nombre) }}"
                            {{ old('tipo_articulo_id') == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->nombre }}
                    </option>
                @endforeach
            </select>
            @error('tipo_articulo_id') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div id="costo-servicio-wrap" style="display:none;">
            <div class="form-group">
                <label for="precio_costo">Costo estimado del servicio (Gs.)</label>
                <div class="input-wrap">
                    <i data-lucide="wrench" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                    <input type="number" id="precio_costo" name="precio_costo"
                           value="{{ old('precio_costo') }}" min="0" step="any" placeholder="0">
                </div>
                <p style="font-size:11px;color:#a08c78;margin-top:6px;">
                    Costo estimado por unidad de uso (desgaste, insumo, etc.)
                </p>
                @error('precio_costo') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="unidad_medida_id">Unidad de medida</label>
            <select id="unidad_medida_id" name="unidad_medida_id" required>
                <option value="">— Seleccioná una unidad —</option>
                @foreach($unidades as $unidad)
                    <option value="{{ $unidad->id }}" {{ old('unidad_medida_id') == $unidad->id ? 'selected' : '' }}>
                        {{ $unidad->nombre }} ({{ $unidad->abreviatura }})
                    </option>
                @endforeach
            </select>
            @error('unidad_medida_id') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="foto">Foto</label>
            <div class="file-input-wrap">
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>
            @error('foto') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div style="margin-top: 28px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" style="width:14px;height:14px;"></i>
                Guardar
            </button>
            <a href="{{ route('articulos.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
(function () {
    const selTipo  = document.getElementById('tipo_articulo_id');
    const costoWrap = document.getElementById('costo-servicio-wrap');

    function toggleCosto() {
        const opt = selTipo.options[selTipo.selectedIndex];
        const nombre = opt ? (opt.dataset.nombre || '') : '';
        costoWrap.style.display = nombre.includes('servic') ? 'block' : 'none';
    }

    selTipo.addEventListener('change', toggleCosto);
    toggleCosto();
})();
</script>

@endsection
