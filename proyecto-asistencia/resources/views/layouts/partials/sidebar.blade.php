<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar bg-primary">
        <a class="sidebar-brand" href="{{ url('/about') }}">
            <img src="{{ asset('images/logo_final.png') }}" class="img-fluid" alt="Descripción de la imagen">
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-item bg-primary">
                <a class="sidebar-link bg-primary" href="{{ url('/dashboard') }}">
                    <i class="align-middle bg-primary text-white" data-feather="home"></i> <span class="align-middle text-white">Menú principal</span>
                </a>
            </li>
            <li class="sidebar-item bg-primary">
                <a class="sidebar-link bg-primary" href="{{ url('groups/create') }}">
                    <i class="align-middle bg-primary text-white" data-feather="plus-circle"></i> <span class="align-middle text-white">Crear Grupos</span>
                </a>
            </li>
            <li class="sidebar-item bg-primary">
                <a class="sidebar-link bg-primary" href="{{ url('/groups') }}">
                    <i class="align-middle bg-primary text-white" data-feather="users"></i> <span class="align-middle text-white">Mis Grupos</span>
                </a>
            </li>
            <!-- Añade más elementos del menú aquí -->
        </ul>
    </div>
</nav>
