<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Portofolio Website Given')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Theme Initialization Script (Zero-FOUC) -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans min-h-screen flex flex-col justify-between transition-colors duration-200">
        <div>
            @include('partials.navbar')

            @isset($header)
                <header class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="container mx-auto px-4 py-4 max-w-5xl">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="container mx-auto px-4 py-6 max-w-5xl">
                @include('partials._flash')
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>

        @include('partials.footer')
        @stack('scripts')
    </body>
</html>
