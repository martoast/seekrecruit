<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seek & Recruit Network')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="flex flex-col min-h-screen bg-gray-50">
        @include('partials.navbar')
        @include('partials.mobile-menu')

        <main class="flex-1">
            @yield('content')
        </main>

        @hasSection('hide-footer')
        @else
            @include('partials.footer')
        @endif

        @include('partials.toast')
    </div>
</body>
</html>
