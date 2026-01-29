<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú - @yield('title', 'Aplicación')</title>
    @include('partials.head')
</head>
<body>



@include('partials.menu')


<div class="container-fluid">
    <main class="p-4">
        @if(session('status'))
            <div class="alert alert-info small">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
