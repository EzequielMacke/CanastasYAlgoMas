@if(!session('usuario'))
    <script>window.location.href = '{{ route('login.form') }}';</script>
@endif
<!-- Offcanvas Sidebar -->
<nav class="navbar navbar-expand-lg navbar-light modern-navbar shadow-sm">
    <div class="container-fluid">
        <button class="btn btn-light me-2 modern-hamburger" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Menú">
            <i class="bi bi-list" style="font-size:1.7rem;"></i>
        </button>
        <a class="navbar-brand fw-bold text-primary" href="#" style="letter-spacing:0.03em;">CanastasYAlgoMas</a>
        <ul class="navbar-nav ms-auto flex-row align-items-center">
            @if(session('usuario'))
                <li class="nav-item me-2">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="bi bi-person-circle me-1 text-primary" style="font-size:1.3rem;"></i>
                        <span class="fw-semibold">{{ session('usuario.nombre') }} {{ session('usuario.apellido') }}</span>
                        <span class="badge bg-light text-dark ms-2 border border-1 border-primary">
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
                        <button type="button" class="btn btn-outline-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#logoutModal">Cerrar sesión</button>
                    </form>
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
<div class="offcanvas offcanvas-start modern-sidebar" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold text-primary" id="sidebarMenuLabel">Menú</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column p-3">
            <a class="nav-link modern-link {{ request()->routeIs('home') ? 'active' : '' }}" href="#"><i class="bi bi-house-door me-2"></i>Inicio</a>
            <a class="nav-link modern-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}"><i class="bi bi-people me-2"></i>Usuarios</a>
            <a class="nav-link modern-link {{ request()->routeIs('permisos.*') ? 'active' : '' }}" href="#"><i class="bi bi-shield-lock me-2"></i>Permisos</a>
            <a class="nav-link modern-link {{ request()->routeIs('modulos.*') ? 'active' : '' }}" href="#"><i class="bi bi-grid-1x2 me-2"></i>Módulos</a>
            <a class="nav-link modern-link {{ request()->routeIs('depositos.*') ? 'active' : '' }}" href="#"><i class="bi bi-box-seam me-2"></i>Depósitos</a>
            <a class="nav-link modern-link {{ request()->routeIs('sucursales.*') ? 'active' : '' }}" href="#"><i class="bi bi-building me-2"></i>Sucursales</a>
            <a class="nav-link modern-link {{ request()->routeIs('cargos.*') ? 'active' : '' }}" href="#"><i class="bi bi-person-badge me-2"></i>Cargos</a>
        </nav>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .modern-navbar {
        background: #fff;
        border-bottom: 1px solid #e5e5e5;
        border-radius: 0 0 1.2rem 1.2rem;
        padding-top: 0.2rem;
        padding-bottom: 0.2rem;
    }
    .modern-hamburger {
        border-radius: 0.7rem;
        border: 1px solid #e5e5e5;
        background: #f8f9fa;
        transition: background 0.2s, box-shadow 0.2s;
    }
    .modern-hamburger:hover {
        background: #e0eafc;
        box-shadow: 0 2px 8px #0d6efd22;
    }
    .modern-sidebar {
        background: #fff;
        border-right: 1px solid #e5e5e5;
        border-radius: 0 1.2rem 1.2rem 0;
        min-width: 220px;
    }
    .modern-link {
        color: #222;
        font-weight: 500;
        border-radius: 0.7rem;
        margin-bottom: 0.2rem;
        padding: 0.6rem 1.1rem;
        transition: background 0.18s, color 0.18s;
        font-size: 1.04rem;
        display: flex;
        align-items: center;
    }
    .modern-link i {
        font-size: 1.2rem;
        opacity: 0.8;
    }
    .modern-link.active, .modern-link:hover {
        background: #e0eafc;
        color: #0d6efd;
    }
    .modern-link.active i, .modern-link:hover i {
        color: #0d6efd;
        opacity: 1;
    }
    .navbar-brand {
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        font-size: 1.25rem;
    }
    .badge.bg-light.text-dark {
        font-size: 0.95em;
        background: #f8f9fa !important;
        color: #222 !important;
        border-radius: 0.7rem;
        border: 1px solid #e5e5e5;
    }
</style>
