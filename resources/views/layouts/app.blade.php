<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canastas y Algo Más — @yield('titulo', 'Panel')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    @stack('styles')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background-color: #f5f0eb;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #2c2117;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Navbar ── */
        nav {
            background: #2c2117;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 58px;
            border-bottom: 1px solid #1a1208;
        }

        .nav-brand {
            color: #f5f0eb;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.02em;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-brand svg { opacity: 0.85; }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }

        .nav-links a {
            color: #c4b8ac;
            text-decoration: none;
            font-size: 13px;
            font-weight: 400;
            padding: 6px 14px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.15s, color 0.15s;
        }

        .nav-links a:hover { background: rgba(255,255,255,0.07); color: #f5f0eb; }
        .nav-links a.activo { background: rgba(255,255,255,0.1); color: #f5f0eb; }

        .nav-right { display: flex; align-items: center; gap: 16px; }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            font-weight: 500;
            color: #a08c78;
            letter-spacing: 0.02em;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 500;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: #c4b8ac;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s, border-color 0.15s;
        }

        .btn-logout:hover { background: rgba(255,255,255,0.12); color: #f5f0eb; border-color: rgba(255,255,255,0.2); }

        /* ── Contenido ── */
        main { max-width: 980px; margin: 40px auto; padding: 0 24px; }

        /* ── Flash ── */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #eef7f1;
            border: 1px solid #c2dfc9;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 500;
            color: #2e5a38;
            margin-bottom: 24px;
        }

        .flash svg { flex-shrink: 0; color: #5a9a6a; }

        /* ── Card ── */
        .card {
            background: #fff;
            border: 1px solid #e8e0d8;
            border-radius: 10px;
            padding: 36px 40px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.04);
        }

        /* ── Encabezado de sección ── */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .section-header h1 {
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -0.01em;
            color: #1a1208;
        }

        /* ── Botones ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 7px;
            cursor: pointer;
            border: none;
            transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
            white-space: nowrap;
        }

        .btn:active { transform: scale(0.98); }

        .btn-primary {
            background: #2c2117;
            color: #f5f0eb;
            box-shadow: 0 1px 3px rgba(44,33,23,0.3);
        }

        .btn-primary:hover {
            background: #3e3020;
            box-shadow: 0 3px 8px rgba(44,33,23,0.25);
        }

        .btn-secondary {
            background: #fff;
            color: #4a3a2a;
            border: 1px solid #d8cfc7;
        }

        .btn-secondary:hover { background: #fdfaf7; border-color: #b5a898; }

        .btn-danger {
            background: #fff5f5;
            color: #c0392b;
            border: 1px solid #f5c6c4;
        }

        .btn-danger:hover { background: #ffeaea; border-color: #e8a8a6; }

        .btn-success {
            background: #f0faf3;
            color: #27794a;
            border: 1px solid #b8dfc6;
        }

        .btn-success:hover { background: #e2f5e8; border-color: #8fcba8; }

        .btn-sm { padding: 6px 13px; font-size: 12px; }

        /* ── Formularios ── */
        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #6b5744;
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrap svg {
            position: absolute;
            left: 12px;
            color: #c4b8ac;
            pointer-events: none;
        }

        .input-wrap input { padding-left: 38px; }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d8cfc7;
            border-radius: 7px;
            background: #fdfaf7;
            font-size: 14px;
            font-family: inherit;
            color: #2c2117;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #a08c78;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(160,140,120,0.15);
        }

        .field-error { font-size: 12px; color: #c0392b; margin-top: 6px; display: flex; align-items: center; gap: 5px; }

        select,
        textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d8cfc7;
            border-radius: 7px;
            background: #fdfaf7;
            font-size: 14px;
            font-family: inherit;
            color: #2c2117;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        select:focus,
        textarea:focus {
            border-color: #a08c78;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(160,140,120,0.15);
        }

        textarea { resize: vertical; min-height: 90px; }

        .file-input-wrap {
            position: relative;
        }

        .file-input-wrap input[type="file"] {
            width: 100%;
            padding: 9px 14px;
            border: 1px solid #d8cfc7;
            border-radius: 7px;
            background: #fdfaf7;
            font-size: 13px;
            font-family: inherit;
            color: #6b5744;
            cursor: pointer;
            outline: none;
        }

        .file-input-wrap input[type="file"]:focus {
            border-color: #a08c78;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(160,140,120,0.15);
        }

        .foto-preview {
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #e8e0d8;
            max-width: 160px;
            display: block;
        }

        /* ── Tabla ── */
        table { width: 100%; border-collapse: collapse; font-size: 14px; }

        th {
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #a08c78;
            padding: 10px 14px;
            border-bottom: 1px solid #ede7df;
        }

        td {
            padding: 13px 14px;
            border-bottom: 1px solid #f5f0eb;
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fdfaf7; }

        .td-acciones { display: flex; gap: 8px; align-items: center; justify-content: flex-end; }

        .badge-id {
            font-size: 11px;
            font-weight: 600;
            color: #c4b8ac;
            background: #f5f0eb;
            border-radius: 4px;
            padding: 2px 7px;
        }

        /* ── Tabs ── */
        .tabs {
            display: flex;
            gap: 2px;
            margin-bottom: 0;
            padding: 16px 24px 0;
            border-bottom: 1px solid #ede7df;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            padding: 10px 18px;
            margin-bottom: -1px;
            cursor: pointer;
            color: #a08c78;
            text-decoration: none;
            border-radius: 6px 6px 0 0;
            transition: color 0.15s, background 0.15s;
        }

        .tab-btn:hover { color: #2c2117; background: #fdfaf7; }

        .tab-btn.activo {
            color: #2c2117;
            border-bottom-color: #2c2117;
            background: transparent;
        }

        .tab-count {
            font-size: 11px;
            font-weight: 600;
            background: #f5f0eb;
            color: #a08c78;
            padding: 1px 7px;
            border-radius: 10px;
        }

        .tab-btn.activo .tab-count { background: #2c2117; color: #f5f0eb; }

        .tab-content { padding: 28px 32px 36px; }

        .empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 48px 0;
            color: #c4b8ac;
            font-size: 13px;
        }

        .empty svg { color: #ddd5cc; }
    </style>
</head>
<body>

<nav>
    <a href="/" class="nav-brand">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Canastas y Algo Más
    </a>

    <ul class="nav-links">
        <li>
            <a href="{{ route('ventas.index') }}" class="{{ request()->routeIs('ventas.*') ? 'activo' : '' }}">
                <i data-lucide="receipt" style="width:15px;height:15px;"></i>
                Ventas
            </a>
        </li>
        <li>
            <a href="{{ route('catalogo.index') }}" class="{{ request()->routeIs('catalogo.*') ? 'activo' : '' }}">
                <i data-lucide="store" style="width:15px;height:15px;"></i>
                Catálogo
            </a>
        </li>
        @if(auth()->user()->nick === 'admin')
            <li>
                <a href="{{ route('vendedores.index') }}" class="{{ request()->routeIs('vendedores.*') ? 'activo' : '' }}">
                    <i data-lucide="users" style="width:15px;height:15px;"></i>
                    Vendedores
                </a>
            </li>
            <li>
                <a href="{{ route('articulos.index') }}" class="{{ request()->routeIs('articulos.*') ? 'activo' : '' }}">
                    <i data-lucide="package" style="width:15px;height:15px;"></i>
                    Artículos
                </a>
            </li>
            <li>
                <a href="{{ route('recetas.index') }}" class="{{ request()->routeIs('recetas.*') ? 'activo' : '' }}">
                    <i data-lucide="chef-hat" style="width:15px;height:15px;"></i>
                    Recetas
                </a>
            </li>
            <li>
                <a href="{{ route('ingresos.index') }}" class="{{ request()->routeIs('ingresos.*') ? 'activo' : '' }}">
                    <i data-lucide="arrow-down-to-line" style="width:15px;height:15px;"></i>
                    Ingresos
                </a>
            </li>
            <li>
                <a href="{{ route('stock.index') }}" class="{{ request()->routeIs('stock.*') ? 'activo' : '' }}">
                    <i data-lucide="warehouse" style="width:15px;height:15px;"></i>
                    Inventario
                </a>
            </li>
            <li>
                <a href="{{ route('precios-venta.index') }}" class="{{ request()->routeIs('precios-venta.*') ? 'activo' : '' }}">
                    <i data-lucide="tag" style="width:15px;height:15px;"></i>
                    Precios
                </a>
            </li>
            <li>
                <a href="{{ route('comisiones.index') }}" class="{{ request()->routeIs('comisiones.*') ? 'activo' : '' }}">
                    <i data-lucide="percent" style="width:15px;height:15px;"></i>
                    Comisiones
                </a>
            </li>
            <li>
                <a href="{{ route('apertura-caja.index') }}" class="{{ request()->routeIs('apertura-caja.*') ? 'activo' : '' }}">
                    <i data-lucide="store" style="width:15px;height:15px;"></i>
                    Caja
                </a>
            </li>
            <li>
                <a href="{{ route('finanzas.index') }}" class="{{ request()->routeIs('finanzas.*') ? 'activo' : '' }}">
                    <i data-lucide="wallet" style="width:15px;height:15px;"></i>
                    Finanzas
                </a>
            </li>
        @endif
    </ul>

    <div class="nav-right">
        <span class="nav-user">
            <i data-lucide="circle-user" style="width:15px;height:15px;color:#6b5744;"></i>
            {{ auth()->user()->nick }}
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i data-lucide="log-out" style="width:14px;height:14px;"></i>
                Salir
            </button>
        </form>
    </div>
</nav>

<main>
    @if(session('exito'))
        <div class="flash">
            <i data-lucide="circle-check" style="width:16px;height:16px;"></i>
            {{ session('exito') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fff0ee;border:1px solid #f5c6c4;border-radius:8px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;gap:10px;font-size:13px;font-weight:500;color:#c0392b;">
            <i data-lucide="circle-alert" style="width:16px;height:16px;flex-shrink:0;"></i>
            {{ session('error') }}
        </div>
    @endif

    @if(session('error_apertura'))
        <div style="background:#fff5e6;border:1px solid #f5ddb0;border-radius:8px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:center;gap:10px;font-size:13px;font-weight:500;color:#b45309;">
            <i data-lucide="triangle-alert" style="width:16px;height:16px;flex-shrink:0;"></i>
            {{ session('error_apertura') }}
        </div>
    @endif

    @if(session('errores_stock'))
        <div style="background:#fff5f5;border:1px solid #f5c6c4;border-radius:8px;padding:16px 20px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#c0392b;margin-bottom:10px;">
                <i data-lucide="triangle-alert" style="width:16px;height:16px;flex-shrink:0;"></i>
                Stock insuficiente para completar la producción
            </div>
            <ul style="list-style:none;display:flex;flex-direction:column;gap:5px;">
                @foreach(session('errores_stock') as $err)
                    <li style="font-size:13px;color:#7a2a2a;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                        <i data-lucide="x-circle" style="width:13px;height:13px;color:#c0392b;flex-shrink:0;"></i>
                        <strong>{{ $err['nombre'] }}</strong>:
                        necesario&nbsp;<strong>{{ $err['necesario'] }}&nbsp;{{ $err['unidad'] }}</strong>,
                        disponible&nbsp;{{ $err['disponible'] }}&nbsp;{{ $err['unidad'] }}
                        &nbsp;<span style="color:#c0392b;font-weight:600;">(faltan&nbsp;{{ $err['falta'] }}&nbsp;{{ $err['unidad'] }})</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('contenido')
</main>

<script>lucide.createIcons();</script>
</body>
</html>
