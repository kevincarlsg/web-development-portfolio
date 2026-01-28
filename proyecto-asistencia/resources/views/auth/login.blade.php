<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-3g9smkdcKChJ7B50hKd1xAO9zTA5lA9bu1ynf00CspJS+FJAd7E6CyBk3r9Vfz6n" crossorigin="anonymous">

    <!-- AdminKit CSS (Local) -->
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">

    <!-- Custom CSS (Opcional) -->
    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden; /* Evita el desplazamiento horizontal */
        }
        .login-section {
            height: 100%;
            overflow: hidden; /* Evita el desplazamiento no deseado */
        }
        .login-left {
            position: relative;
            height: 100%;
            overflow: hidden; /* Evita el scroll si la imagen se sale del contenedor */
        }
        .login-left::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://img.freepik.com/fotos-premium/codigo-qr-fondo-azul-renderizado-3d_975254-1118.jpg');
            background-size: cover; /* La imagen cubre toda la columna */
            background-position: center; /* Centra la imagen en la columna */
            filter: blur(8px); /* Aplica el desenfoque a la imagen */
            transform: scale(1.1); /* Aumenta el tamaño para asegurarse de que no queden bordes visibles después del blur */
        }
        .login-right {
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 4rem;
            height: 100%; /* Ajusta la altura para que sea igual a la altura de la columna */
            overflow: hidden; /* Evita el desplazamiento no deseado */
        }
        .login-logo {
            margin-bottom: 2rem;
        }
        /* Custom styles for the toast */
        .custom-toast {
            border-radius: 12px; /* Hacer el toast redondeado */
            min-height: 70px; /* Incrementar la altura del toast */
            padding: 1rem; /* Añadir algo de espacio interno */
        }
    </style>
</head>
<body>

    <div class="container-fluid login-section">
        <div class="row h-100 gx-0"> <!-- Se añade gx-0 para eliminar cualquier espacio horizontal no deseado -->
            <!-- Left Side - Promotional Section -->
            <div class="col-md-7 login-left d-none d-md-block"></div>

            <!-- Right Side - Login Form -->
            <div class="col-md-5 d-flex align-items-center justify-content-center login-right">
                <div style="max-width: 400px; width: 100%;">
                    <div class="text-center login-logo">
                        <!-- Logo with link to home -->
                        <a href="/">
                            <img src="{{ asset('images/Recurso.png') }}" alt="Logo" width="150">
                        </a>
                    </div>
                    <h2 class="text-center mb-4">Iniciar Sesión</h2>
                    
                    <!-- Formulario de Inicio de Sesión -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   type="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Remember Me and Forgot Password -->
                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Recuérdame</label>
                            </div>
                            <div>
                                <a href="{{ route('password.request') }}" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>

                        <!-- Sign In Button -->
                        <div class="mb-3">
                            <button class="btn btn-outline-primary w-100">Iniciar Sesión</button>
                        </div>
                        
                        <!-- Register Link -->
                        <div class="text-center">
                            <p>¿No tienes una cuenta? <a href="{{ route('register') }}" class="text-decoration-none">Regístrate aquí</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification (Bootstrap Toast) -->
    @if ($errors->any())
    <div class="position-fixed top-0 end-0 p-2" style="z-index: 1055">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0 shadow-lg custom-toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ppY1MJxrHChx1YWObpRFOB5tu6FopO1wsyucog/7m5SbPbcg9kVtftT7n2B5r5z3" crossorigin="anonymous"></script>

    <!-- AdminKit JS (Local) -->
    <script src="{{ asset('adminkit/js/app.js') }}"></script>

    <!-- Toast JS to make it disappear after 6 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const toastElement = document.getElementById('errorToast');
            if (toastElement) {
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 6000 // 6000 milisegundos = 6 segundos
                });
                toast.show();
            }
        });
    </script>
</body>
</html>
