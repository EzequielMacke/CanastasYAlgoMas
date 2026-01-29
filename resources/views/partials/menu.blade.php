@if(!session('usuario'))
    <script>window.location.href = '{{ route('login.form') }}';</script>
@endif
<!-- Offcanvas Sidebar -->
<nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid">
        <!-- Botón hamburguesa para abrir el menú -->
        <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Menú">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">CanastasYAlgoMas</a>
        <ul class="navbar-nav ms-auto flex-row align-items-center">
            @if(session('usuario'))
                <li class="nav-item me-2">
                    <a class="nav-link" href="#">
                        {{ session('usuario.nombre') }} {{ session('usuario.apellido') }}
                        <span class="badge bg-secondary ms-1">
                            @php
                                $cargo = null;
                                if(session('usuario.id_cargo')) {
                                    $cargo = \App\Models\Cargo::find(session('usuario.id_cargo'));
                                }
                            @endphp
                            {{ $cargo ? $cargo->descripcion : 'Sin cargo' }}
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline" id="logoutForm">
                        @csrf
                        <button type="button" class="btn btn-sm btn-outline-light ms-2" data-bs-toggle="modal" data-bs-target="#logoutModal">Cerrar sesión</button>
                    </form>

                    <!-- Modal de confirmación de cierre de sesión -->
                    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="logoutModalLabel">Confirmar cierre de sesión</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Desea cerrar sesión?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('logoutForm').submit();">Cerrar sesión</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @else
                <li class="nav-item"><a class="nav-link" href="#">Iniciar sesión</a></li>
            @endif
        </ul>
    </div>
</nav>
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">Menú</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column p-3">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="#">Inicio</a>
            <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">Usuarios</a>
            <a class="nav-link {{ request()->routeIs('permisos.*') ? 'active' : '' }}" href="#">Permisos</a>
            <a class="nav-link {{ request()->routeIs('modulos.*') ? 'active' : '' }}" href="#">Módulos</a>
            <a class="nav-link {{ request()->routeIs('depositos.*') ? 'active' : '' }}" href="#">Depósitos</a>
            <a class="nav-link {{ request()->routeIs('sucursales.*') ? 'active' : '' }}" href="#">Sucursales</a>
            <a class="nav-link {{ request()->routeIs('cargos.*') ? 'active' : '' }}" href="#">Cargos</a>
        </nav>
    </div>
</div>
