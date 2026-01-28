
<!DOCTYPE html>
<html lang="es">
<!--
/**
*Vista Header
*HASH
*Autor: Gadiel Palma Ramos
* Abdiel Salgado Salinas
*Kevin Carlos Sánchez González
*Ricardo Flores Garduño
*Axel Itech Apolonio Flores
*Roberto Héctor Díaz Coyoli 
*Daniel Pérez Muñoz
*Juan Carlos Dzib Gochy
*Fecha de creación: 13/11/2024
*/
-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida - Nuestra Empresa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- AdminKit CSS (Local) -->
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }

        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            font-weight: bold;
            color: #2271b3 !important;
        }

        .navbar-custom .nav-link {
            color: #2271b3 !important;
        }

        .video-bg-container {
            position: relative;
            height: 90vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: black;
        }

        .video-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        iframe#youtube-video {
            width: 100vw;
            /* Aspect ratio 16:9 */
            height: 56.25vw; 
            min-height: 100vh;
            /* Aspect ratio inverted 9:16 */
            min-width: 177.77vh; 
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .welcome-content {
            position: relative;
            z-index: 2;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }

        .welcome-content h1 {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .welcome-content p {
            font-size: 1.5rem;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        }

        .btn-explore {
            background-color: #2271b3;
            color: #ffffff;
            border: 2px solid #2271b3;
            padding: 10px 25px;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-size: 1.1rem;
        }

        .btn-explore:hover {
            background-color: #195a88;
            color: #ffffff;
        }

        footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 15px 0;
            text-align: center;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/Recurso.png') }}" alt="Logo Empresa" class="me-2" style="width: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary" href="{{ route('register') }}">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Video Background Section -->
    <div class="video-bg-container">
        <div class="video-wrapper">
            <!-- YouTube iframe as Video Background -->
            <iframe id="youtube-video" src="https://www.youtube.com/embed/aQfARKcyu0w?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&modestbranding=1&playlist=aQfARKcyu0w" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
        </div>
        <div class="container welcome-content">
            <h1 class="text-center mb-4 text-white">Bienvenidos a Nuestra Empresa</h1>
            <p>Innovación, Calidad y Resultados</p>
            <a href="#services-section" class="btn btn-primary">Explorar Servicios</a>
        </div>
    </div>

    <!-- About Section -->
    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-4 text-primary">Acerca de Nosotros</h2>
                    <p class="lead">En nuestra empresa de software, nos apasiona la creación de soluciones tecnológicas innovadoras y de alta calidad. Creemos en la tecnología como el motor para el cambio positivo y ayudamos a nuestros clientes a alcanzar sus objetivos.</p>
                    <p>Estamos comprometidos con la excelencia y la innovación, trabajando estrechamente con nuestros clientes para entender sus necesidades y ofrecer soluciones que realmente marquen la diferencia en sus negocios.</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f" alt="Nosotros" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section bg-light py-5" id="services-section">
        <div class="container">
            <h2 class="mb-4 text-center text-primary">Nuestros Servicios</h2>
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card card-custom p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-laptop" style="font-size: 2.5rem; color: #2271b3;"></i>
                        </div>
                        <h3>Desarrollo Web</h3>
                        <p>Creación de aplicaciones web responsivas y eficientes para su negocio.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card card-custom p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-phone" style="font-size: 2.5rem; color: #2271b3;"></i>
                        </div>
                        <h3>Aplicaciones Móviles</h3>
                        <p>Soluciones móviles para Android e iOS, adaptadas a sus necesidades.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card card-custom p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-bar-chart" style="font-size: 2.5rem; color: #2271b3;"></i>
                        </div>
                        <h3>Consultoría Digital</h3>
                        <p>Asesoramiento para mejorar la presencia digital y optimizar procesos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <section class="faq-section py-5" id="faq-section">
        <div class="container">
            <h2 class="faq-title text-center mb-5 text-primary">Preguntas Frecuentes sobre ClassScan</h2>
            <div class="accordion" id="accordionFaq">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            ¿Cómo funciona el sistema de escaneo con código QR?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFaq">
                        <div class="accordion-body">
                            El sistema permite a los usuarios escanear un código QR generado automáticamente para registrar su asistencia. Cada grupo tiene un código QR único que es escaneado por los estudiantes utilizando la aplicación ClassScan.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            ¿Qué es ClassScan y cómo lo utilizo?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFaq">
                        <div class="accordion-body">
                            ClassScan es una herramienta para estudiantes y profesores que facilita la gestión de asistencia mediante el uso de códigos QR. Los profesores pueden generar estos códigos para sus clases y los estudiantes los escanean para marcar su presencia.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            ¿Puedo utilizar ClassScan en múltiples dispositivos?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFaq">
                        <div class="accordion-body">
                            Sí, ClassScan está diseñado para ser utilizado en múltiples dispositivos. Los estudiantes pueden utilizar cualquier smartphone con una cámara para escanear el código QR y registrar su asistencia de manera sencilla y rápida.
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <p class="mb-3">¿Aún tienes dudas?</p>
                <a href="#" class="btn btn-outline-primary">Ver más preguntas</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2024 Hash. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
