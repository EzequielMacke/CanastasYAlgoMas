<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión</title>
    @include('partials.head')
</head>
<body class="bg-light">
    <div class="container vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-sm w-100" style="max-width:420px;">
            <div class="card-body">
                <h4 class="card-title mb-3 text-center">Iniciar sesión</h4>

                @if(session('status'))
                    <div class="alert alert-info small">{{ session('status') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="nick" class="form-label">Usuario</label>
                        <input id="nick" type="text" name="nick" value="{{ old('nick') }}" required autofocus
                               class="form-control @error('nick') is-invalid @enderror" placeholder="Ingrese su usuario">
                        @error('nick')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input id="password" type="password" name="password" required
                               class="form-control @error('password') is-invalid @enderror" placeholder="********">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="remember">Recuérdame</label>
                        </div>
                        <a href="#" class="small">¿Olvidaste tu contraseña?</a>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>

                <div class="text-center mt-3 small text-muted">
                    ¿No tienes cuenta? <a href="#">Regístrate</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
