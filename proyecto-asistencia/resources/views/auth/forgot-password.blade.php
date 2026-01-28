<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>

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
        .reset-section {
            height: 100vh;
            overflow: hidden; /* Evita el desplazamiento no deseado */
        }
        .reset-left {
            position: relative;
            height: 100%;
            overflow: hidden; /* Evita el scroll si la imagen se sale del contenedor */
        }
        .reset-left::before {
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
        .reset-right {
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 4rem;
            height: 100%; /* Ajusta la altura para que sea igual a la altura de la columna */
            overflow: hidden; /* Evita el desplazamiento no deseado */
        }
        .reset-logo {
            margin-bottom: 2rem;
        }

        /* Estilos para el Toast personalizado centrado */
        .toast-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1055; /* Por encima de otros elementos */
            max-width: 400px;
            width: 90%; /* Asegura responsividad en dispositivos pequeños */
        }
        .toast-body {
            font-size: 1.2rem; /* Aumenta el tamaño del texto */
            color: #ffffff; /* Asegura que el texto sea blanco */
            text-align: center; /* Centra el texto */
        }
        .toast-header {
            border-bottom: none; /* Elimina el borde inferior del header del toast */
            justify-content: center; /* Centra el contenido del header */
        }

        /* Estilos del botón dentro del Toast */
        .btn-aceptar {
            margin-top: 1rem;
            align-self: center; /* Centra el botón en el toast */
            background-color: #ffffff; /* Fondo blanco */
            border: 2px solid #2271B3; /* Contorno azul */
            color: #2271B3; /* Texto azul */
            padding: 10px 20px; /* Espaciado dentro del botón */
            border-radius: 5px; /* Bordes redondeados */
            font-size: 1rem; /* Tamaño del texto */
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease; /* Transición suave */
        }

        .btn-aceptar:hover {
            background-color: #2271B3; /* Fondo azul al pasar el mouse */
            color: #ffffff; /* Texto blanco al pasar el mouse */
            cursor: pointer; /* Cambia el cursor a puntero */
            transform: scale(1.05); /* Efecto de agrandamiento al pasar el mouse */
        }

        .btn-aceptar:focus {
            outline: none; /* Elimina el contorno de enfoque */
        }

        .iniciar-sesion {
            margin-top: 1rem;
            text-align: left; /* Alinea el texto a la izquierda */
        }
        .iniciar-sesion a {
            color: #2271B3; /* Color azul más claro */
            text-decoration: none;
            font-weight: bold;
        }
        .iniciar-sesion a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container-fluid reset-section">
        <div class="row h-100 gx-0"> <!-- Se añade gx-0 para eliminar cualquier espacio horizontal no deseado -->
            <!-- Left Side - Promotional Section -->
            <div class="col-md-7 reset-left d-none d-md-block"></div>

            <!-- Right Side - Reset Password Form -->
            <div class="col-md-5 d-flex align-items-center justify-content-center reset-right">
                <div style="max-width: 400px; width: 100%;">
                    <div class="text-center reset-logo">
                        <a href="/">
                            <img src="{{ asset('images/Recurso.png') }}" alt="Logo" width="150">
                        </a>
                    </div>
                    <h2 class="text-center mb-4">Restablecer Contraseña</h2>

                    <!-- Descriptive Text -->
                    <div class="mb-4 text-sm text-muted text-center">
                        ¿Ha olvidado su contraseña? No se preocupe. Indíquenos su dirección de correo electrónico y le enviaremos un enlace para restablecer la contraseña que le permitirá elegir una nueva.
                    </div>

                    <!-- Reset Password Form -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Iniciar Sesión Link -->
                        <div class="iniciar-sesion">
                            <a href="{{ route('login') }}">Iniciar sesión</a>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-outline-primary" id="submitButton">
                                Enviar enlace al correo electrónico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast HTML -->
    @if (session('status'))
    <div class="toast-container">
        <div id="emailSentToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex flex-column p-3">
                <div class="toast-body">
                    Su correo ha sido enviado correctamente.
                </div>
                <button type="button" class="btn btn-light btn-aceptar" id="acceptButton">Aceptar</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ppY1MJxrHChx1YWObpRFOB5tu6FopO1wsyucog/7m5SbPbcg9kVtftT7n2B5r5z3" crossorigin="anonymous"></script>

    <!-- AdminKit JS (Local) -->
    <script src="{{ asset('adminkit/js/app.js') }}"></script>

    <!-- Custom Script para Mostrar el Toast -->
    @if (session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('emailSentToast');
            var toast = new bootstrap.Toast(toastEl, { 
                autohide: false // Evita que el toast se oculte automáticamente
            });
            toast.show();

            // Redireccionar al login al hacer clic en "Aceptar"
            document.getElementById('acceptButton').addEventListener('click', function () {
                window.location.href = '{{ route("login") }}';
            });
        });
    </script>
    @endif

</body>
</html>
