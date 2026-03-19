@extends('layouts.app')

@section('titulo', 'Editar Vendedor')

@section('contenido')

@php $backTab = $vendedor->estado_id === 1 ? 'activos' : 'inactivos'; @endphp

<div class="section-header">
    <div>
        <h1>Editar vendedor</h1>
        <p style="font-size:13px;color:#a08c78;margin-top:4px;">{{ $vendedor->nombre }} {{ $vendedor->apellido }}</p>
    </div>
    <a href="{{ route('vendedores.index', ['tab' => $backTab]) }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
        Volver
    </a>
</div>

<div class="card" style="max-width: 480px;">
    <form method="POST" action="{{ route('vendedores.update', $vendedor) }}">
        @csrf @method('PUT')

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <div class="input-wrap">
                <i data-lucide="user" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $vendedor->nombre) }}" required autofocus>
            </div>
            @error('nombre') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <div class="input-wrap">
                <i data-lucide="user" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $vendedor->apellido) }}" required>
            </div>
            @error('apellido') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div style="margin-top: 28px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" style="width:14px;height:14px;"></i>
                Guardar cambios
            </button>
            <a href="{{ route('vendedores.index', ['tab' => $backTab]) }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
