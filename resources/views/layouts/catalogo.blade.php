<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo — Canastas y Algo Más</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #f5f0eb;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #2c2117;
            -webkit-font-smoothing: antialiased;
        }

        header {
            background: #2c2117;
            padding: 0 40px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #1a1208;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #f5f0eb;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .brand svg { opacity: 0.85; }

        .brand-sub {
            font-size: 12px;
            color: #a08c78;
            font-weight: 400;
            margin-left: 4px;
        }

        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 24px;
        }

        @stack('styles')
    </style>
</head>
<body>

<header>
    <a href="{{ route('catalogo.index') }}" class="brand">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
            <line x1="3" y1="6" x2="21" y2="6"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        Canastas y Algo Más
        <span class="brand-sub">· Catálogo</span>
    </a>
</header>

<main>
    @yield('contenido')
</main>

<script>lucide.createIcons();</script>
</body>
</html>
