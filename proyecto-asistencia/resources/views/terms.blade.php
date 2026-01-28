

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones y Política de Privacidad</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-3g9smkdcKChJ7B50hKd1xAO9zTA5lA9bu1ynf00CspJS+FJAd7E6CyBk3r9Vfz6n" crossorigin="anonymous">

    <!-- AdminKit CSS -->
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 2rem;
        }
        .container {
            max-width: 900px;
            background: white;
            padding: 2rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            color: #333;
        }
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo img {
            width: 150px;
        }
        .divider {
            border-top: 2px solid #ddd;
            margin: 2rem 0;
        }
        .content-section {
            margin-top: 1rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <!-- Header con Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/Recurso.png') }}" alt="Logo de la Empresa" width="120">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('terms')">Términos y Condiciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('privacy')">Política de Privacidad</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <div class="container mt-5">
        <!-- Sección de Términos y Condiciones -->
        <div id="terms-section" class="content-section">
            
        <h1 class="text-center mb-4 text-primary">Términos y Condiciones</h1>
        <p>Bienvenido a nuestra aplicación. A continuación, describimos nuestros términos y condiciones. Al utilizar nuestro servicio, aceptas cumplir y estar sujeto a los siguientes términos:</p>


        <div id="divider" class="divider"></div>
            <h2>1. Uso del Servicio</h2>
            <p>El uso de nuestros servicios implica la aceptación de estos términos y condiciones. No puedes utilizar nuestra plataforma con fines ilegales o no autorizados.</p>

            <h2>2. Propiedad Intelectual</h2>
            <p>Todos los contenidos, marcas, logotipos, imágenes y recursos pertenecen exclusivamente a nuestra empresa o a nuestros licenciantes. No se permite la copia, distribución o uso sin permiso.</p>

            <h2>3. Modificaciones</h2>
            <p>Nos reservamos el derecho de modificar estos términos y condiciones en cualquier momento sin previo aviso. Las modificaciones entrarán en vigencia en el momento de su publicación.</p>
        </div>


        <!-- Sección de Política de Privacidad -->
        <div id="privacy-section" class="content-section d-none">
            <h1 class="text-center mb-4 text-primary">Política de Privacidad</h1>
            <p>Valoramos tu privacidad y estamos comprometidos con la protección de tus datos personales. A continuación, explicamos cómo recopilamos, utilizamos y protegemos tu información:</p>
            <div id="divider" class="divider"></div>

            <h2>1. Información Recopilada</h2>
            <p>Podemos recopilar datos personales, como tu nombre, correo electrónico y otra información que proporciones al registrarte en nuestro sitio o interactuar con nuestros servicios.</p>

            <h2>2. Uso de la Información</h2>
            <p>La información recopilada se utiliza para proporcionar, mantener y mejorar nuestros servicios, y para comunicarnos contigo de manera eficaz.</p>

            <h2>3. Seguridad</h2>
            <p>Nos esforzamos por proteger tu información personal implementando medidas de seguridad razonables para evitar accesos no autorizados.</p>

            <h2>4. Contacto</h2>
            <p>Si tienes preguntas sobre nuestra política de privacidad, por favor contáctanos a través de la sección de contacto en nuestro sitio web.</p>
        </div>

        <!-- Botón de regreso al inicio -->
        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="btn btn-outline-primary">Regresar al Inicio</a>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ppY1MJxrHChx1YWObpRFOB5tu6FopO1wsyucog/7m5SbPbcg9kVtftT7n2B5r5z3" crossorigin="anonymous"></script>

    <!-- AdminKit JS (Local) -->
    <script src="{{ asset('adminkit/js/app.js') }}"></script>

    <!-- JavaScript para Mostrar/Ocultar Secciones -->
    <script>
        function showSection(section) {
            // Ocultar ambas secciones
            document.getElementById('terms-section').classList.add('d-none');
            document.getElementById('privacy-section').classList.add('d-none');
            document.getElementById('divider').classList.add('d-none');

            // Mostrar la sección seleccionada
            if (section === 'terms') {
                document.getElementById('terms-section').classList.remove('d-none');
                document.getElementById('divider').classList.remove('d-none');
            } else if (section === 'privacy') {
                document.getElementById('privacy-section').classList.remove('d-none');
            }
        }
    </script>

</body>
</html>
