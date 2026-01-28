<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">

            <!-- Notificaciones -->
            
            <!-- Menú de Usuario -->
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->photo ? asset(Auth::user()->photo) : 'https://cdn-icons-png.flaticon.com/512/6073/6073873.png' }}" class="avatar img-fluid rounded-circle me-1" alt="Avatar"> 
                    <span class="text-dark">{{ Auth::user()->name ?? 'Nombre Usuario' }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="/perfil-profesor"><i class="align-middle me-1" data-feather="user"></i> Perfil</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="align-middle me-1" data-feather="log-out"></i> Cerrar Sesión
                    </a>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>                </div>
            </li>
        </ul>
    </div>
</nav>
