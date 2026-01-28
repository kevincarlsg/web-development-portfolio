<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased transition-colors duration-300 bg-green-50 dark:bg-gray-200">
        <div class="min-h-screen bg-gradient-to-r from-green-100 to-green-200 dark:from-gray-200 dark:to-gray-900 text-gray-900 dark:text-gray-200">
    
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-blue-200 bg-opacity-80 dark:bg-gray-200 dark:bg-opacity-80 shadow-md py-5 mb-4 rounded-b-lg">
                    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-blue-700 dark:text-blue-300 text-xl font-semibold">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                <!-- Session Status -->
                <div class="container mx-auto mt-6">
                    @if (session('status'))
                        <div class="bg-blue-100 dark:bg-blue-200 text-blue-800 dark:text-blue-100 p-4 rounded-lg shadow-lg mb-6">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>

                <!-- Main Content Card with Floating Style -->
                <div class="container mx-auto mt-8">
                    <div class="bg-green-200 dark:bg-gray-200 p-8 rounded-xl shadow-2xl transform transition hover:scale-105 duration-300 ease-in-out max-w-4xl mx-auto">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
