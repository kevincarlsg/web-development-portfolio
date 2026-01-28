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
                        <img src="{{ asset('images/Recurso.png') }}" alt="Logo" width="150">
                    </div>
                    <h2 class="text-center mb-4">Restablecer Contraseña</h2>

                    <!-- Descriptive Text -->
                    <div class="mb-4 text-sm text-muted text-center">
                        Ingrese su nueva contraseña y confírmela para restablecer el acceso a su cuenta.
                    </div>

                    <!-- Reset Password Form -->
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-outline-primary">
                                Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ppY1MJxrHChx1YWObpRFOB5tu6FopO1wsyucog/7m5SbPbcg9kVtftT7n2B5r5z3" crossorigin="anonymous"></script>

    <!-- AdminKit JS (Local) -->
    <script src="{{ asset('adminkit/js/app.js') }}"></script>

</body>
</html>
