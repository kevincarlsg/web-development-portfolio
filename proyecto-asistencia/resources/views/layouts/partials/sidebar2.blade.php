<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar bg-primary">
        <a class="sidebar-brand" href="{{ url('/about') }}">
            <img src="{{ asset('images/logo_final.png') }}" class="img-fluid" alt="Descripción de la imagen">
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a class="sidebar-link bg-primary" href="{{ url('/dashboard') }}">
                    <i class="align-middle text-white" data-feather="home"></i> <span class="text-white align-middle">Menú principal</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link bg-primary" href="{{ url('/mis-grupos') }}">
                    <i class="align-middle text-white" data-feather="users"></i> <span class="text-white align-middle">Mis Grupos</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
