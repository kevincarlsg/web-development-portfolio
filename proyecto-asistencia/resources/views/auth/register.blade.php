<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-3g9smkdcKChJ7B50hKd1xAO9zTA5lA9bu1ynf00CspJS+FJAd7E6CyBk3r9Vfz6n" crossorigin="anonymous">

    <!-- AdminKit CSS (Local) -->
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">

    <!-- Custom CSS (Opcional) -->
    <style>
        body {
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        .register-section {
            height: 100vh;
            overflow: hidden;
        }
        .register-left {
            position: relative;
            height: 100%;
            overflow: hidden;
        }
        .register-left::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://img.freepik.com/fotos-premium/codigo-qr-fondo-azul-renderizado-3d_975254-1118.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            transform: scale(1.1);
        }
        .register-right {
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 4rem;
            height: 100%;
            overflow: hidden;
        }
        .register-logo {
            margin-bottom: 2rem;
        }
        /* Custom styles for the toast */
        .custom-toast {
            border-radius: 12px;
            min-height: 70px;
            padding: 1rem;
        }
        /* Custom styles for the password toggle */
        .password-wrapper .input-group .password-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.375rem 0.75rem;
        }
        .password-wrapper .input-group .password-toggle:hover {
            color: #495057;
        }
        .password-wrapper .input-group .password-toggle:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body>

    <!-- SweetAlert para mostrar mensaje de éxito -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: '¡Usuario registrado correctamente!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#4CAF50',
                backdrop: true,
                timer: 5000,
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });

            setTimeout(() => {
                window.location.href = "{{ route('login') }}";
            }, 5000);
        });
    </script>
    @endif

    <!-- Toast Notification (Bootstrap Toast) -->
    @if ($errors->any())
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
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

    <div class="container-fluid register-section">
        <div class="row h-100 gx-0">
            <!-- Left Side - Promotional Section -->
            <div class="col-md-7 register-left d-none d-md-block"></div>

            <!-- Right Side - Register Form -->
            <div class="col-md-5 d-flex align-items-center justify-content-center register-right">
                <div style="max-width: 400px; width: 100%;">
                    <div class="text-center register-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('images/Recurso.png') }}" alt="Logo" width="150">
                        </a>
                    </div>
                    <h2 class="text-center mb-4">Registro de Usuario</h2>

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Hidden Role Field -->
                        <input type="hidden" name="role" value="alumno">

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3 password-wrapper">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password">
                                <button type="button" class="password-toggle input-group-text" onclick="togglePasswordVisibility('password')">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3 password-wrapper">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                                <button type="button" class="password-toggle input-group-text" onclick="togglePasswordVisibility('password_confirmation')">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Accept Terms and Privacy Policy -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="{{ url('/terms') }}" class="text-decoration-none">términos y condiciones</a> y la <a href="{{ url('/terms') }}" class="text-decoration-none">política de privacidad</a>
                                </label>
                            </div>
                        </div>

                        <!-- Register Button and Login Link -->
                        <div class="d-flex items-center justify-content-between mt-4">
                            <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="{{ route('login') }}">
                                ¿Tienes una cuenta?
                            </a>
                            <button type="submit" class="btn btn-outline-primary ms-4">
                                Registrarse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toast JS to make it disappear after 6 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const toastElement = document.getElementById('errorToast');
            if (toastElement) {
                const toast = new bootstrap.Toast(toastElement, {
                    autohide: true,
                    delay: 6000 // 6 segundos
                });
                toast.show();
            }
        });
    </script>

    <!-- JavaScript Validation for Email -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            const emailRegex = /^[a-zA-Z0-9._%+-]+@(alumno\.uaemex\.mx|profesor\.uaemex\.mx|toluca\.tecnm\.mx|utvtol\.edu\.mx|upvm\.edu\.mx|utn\.edu\.mx|unam\.mx|ipn\.mx|itesm\.mx|anahuac\.mx|udg\.mx|correo\.buap\.mx|uanl\.mx)$/;

            emailField.addEventListener('input', function() {
                if (!emailRegex.test(emailField.value)) {
                    emailField.setCustomValidity("El correo no es válido o el dominio no está permitido.");
                } else {
                    emailField.setCustomValidity("");
                }
            });
        });
    </script>

    <!-- JavaScript to Toggle Password Visibility -->
    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        }
    </script>

    <!-- Bootstrap Icons (for password visibility toggle) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</body>
</html>
