<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seek & Recruit Network')</title>
    <meta name="description" content="@yield('meta-description', 'Private recruitment platform for JAE Tijuana and client companies in Baja California. Build your profile and apply to positions.')">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Seek & Recruit">
    <meta property="og:title" content="@yield('og-title', 'Seek & Recruit')">
    <meta property="og:description" content="@yield('og-description', 'Recruitment platform for Baja California. Build your profile and apply to positions.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og-image', asset('images/og-image.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og-title', 'Seek & Recruit')">
    <meta name="twitter:description" content="@yield('og-description', 'Recruitment platform for Baja California. Build your profile and apply to positions.')">
    <meta name="twitter:image" content="@yield('og-image', asset('images/og-image.png'))">

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
