<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portofolio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 font-sans">
    @include('partials.navbar')

    <div class="container mx-auto p-4 max-w-2xl">
        @yield('content')
    </div>

    @include('partials.footer')
    @stack('scripts')
</body>
</html>
