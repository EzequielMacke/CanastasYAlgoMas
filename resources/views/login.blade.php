<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canastas y Algo Más — Ingresar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f0eb;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .card {
            background: #ffffff;
            width: 100%;
            max-width: 380px;
            padding: 44px 40px;
            border: 1px solid #e8e0d8;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.05);
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            background: #2c2117;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: #f5f0eb;
        }

        .brand h1 {
            font-size: 18px;
            font-weight: 600;
            color: #1a1208;
            letter-spacing: -0.01em;
        }

        .brand p {
            font-size: 13px;
            color: #a08c78;
            margin-top: 4px;
            font-weight: 400;
        }

        .divider {
            border: none;
            border-top: 1px solid #ede7df;
            margin-bottom: 28px;
        }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #6b5744;
            margin-bottom: 7px;
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
            width: 16px;
            height: 16px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 14px 10px 38px;
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

        .field-error {
            font-size: 12px;
            color: #c0392b;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-submit {
            width: 100%;
            margin-top: 8px;
            padding: 11px;
            background: #2c2117;
            color: #f5f0eb;
            border: none;
            border-radius: 7px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 1px 3px rgba(44,33,23,0.3);
            transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
        }

        .btn-submit:hover {
            background: #3e3020;
            box-shadow: 0 3px 8px rgba(44,33,23,0.25);
        }

        .btn-submit:active { transform: scale(0.99); }

        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #c4b8ac;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="brand">
            <div class="brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <h1>Canastas y Algo Más</h1>
            <p>Panel de acceso</p>
        </div>

        <hr class="divider">

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label for="nick">Usuario</label>
                <div class="input-wrap">
                    <i data-lucide="user"></i>
                    <input type="text" id="nick" name="nick" value="{{ old('nick') }}" required autofocus autocomplete="off" placeholder="Tu usuario">
                </div>
                @error('nick')
                    <p class="field-error"><i data-lucide="circle-alert" style="width:13px;height:13px;"></i> {{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-wrap">
                    <i data-lucide="lock-keyhole"></i>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i data-lucide="log-in" style="width:16px;height:16px;"></i>
                Ingresar
            </button>
        </form>

        <p class="footer">Canastas y Algo Más &copy; {{ date('Y') }}</p>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
