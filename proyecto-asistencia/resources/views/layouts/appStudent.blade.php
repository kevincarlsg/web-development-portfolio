<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <!-- CSS de AdminKit -->
    <link rel="stylesheet" href="{{ asset('adminkit/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminkit/css/app.css') }}">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar2')

        <!-- Page Content -->
        <div class="main">
            @include('layouts.partials.navbarStudent')

            <main class="content">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- JavaScript de AdminKit -->
    <script src="{{ asset('adminkit/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminkit/js/app.js') }}"></script>
</body>
</html>
