@extends('layouts.app')

@section('titulo', 'Nuevo Vendedor')

@section('contenido')

<div class="section-header">
    <div>
        <h1>Nuevo vendedor</h1>
        <p style="font-size:13px;color:#a08c78;margin-top:4px;">Completá los datos para registrar un vendedor.</p>
    </div>
    <a href="{{ route('vendedores.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
        Volver
    </a>
</div>

<div class="card" style="max-width: 480px;">
    <form method="POST" action="{{ route('vendedores.store') }}">
        @csrf

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <div class="input-wrap">
                <i data-lucide="user" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus placeholder="Ej: María">
            </div>
            @error('nombre') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <div class="input-wrap">
                <i data-lucide="user" style="width:15px;height:15px;left:12px;position:absolute;color:#c4b8ac;pointer-events:none;"></i>
                <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}" required placeholder="Ej: González">
            </div>
            @error('apellido') <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p> @enderror
        </div>

        <div style="margin-top: 28px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" style="width:14px;height:14px;"></i>
                Guardar
            </button>
            <a href="{{ route('vendedores.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@endsection
