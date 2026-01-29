<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de usuarios</title>
    @include('partials.head')
</head>
<body>
    @include('partials.menu')

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Agregar usuario</h2>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver a la lista</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" action="{{ route('usuarios.invitar') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" required placeholder="usuario@email.com" value="{{ old('correo') }}">
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_cargo" class="form-label">Cargo</label>
                            <select name="id_cargo" id="id_cargo" class="form-select select2 @error('id_cargo') is-invalid @enderror" required>
                            <option value="">Seleccione un cargo</option>
                            @foreach($cargos as $cargo)
                                <option value="{{ $cargo->id }}" {{ old('id_cargo') == $cargo->id ? 'selected' : '' }}>{{ $cargo->descripcion }}</option>
                            @endforeach
                        </select>
                        @error('id_cargo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                        <div class="mb-3">
                            <label for="id_sucursal" class="form-label">Sucursal</label>
                            <select name="id_sucursal" id="id_sucursal" class="form-select select2 @error('id_sucursal') is-invalid @enderror" required>
                                <option value="">Seleccione una sucursal</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" {{ old('id_sucursal') == $sucursal->id ? 'selected' : '' }}>{{ $sucursal->descripcion }}</option>
                                @endforeach
                            </select>
                            @error('id_sucursal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Invitar usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap-5'
            });
        });
    </script>
</body>
</html>
