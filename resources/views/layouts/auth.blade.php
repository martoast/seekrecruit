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
    <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-linear-to-br from-primary-50 via-white to-blue-50">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex w-16 h-16 bg-linear-to-br from-primary-500 to-primary-700 rounded-2xl items-center justify-center mb-4 shadow-lg">
                    <span class="text-white font-bold text-2xl">S&R</span>
                </a>
                <h2 class="text-3xl font-bold text-gray-900">@yield('heading')</h2>
                <p class="mt-2 text-gray-600">@yield('subheading')</p>
            </div>

            <x-ui.card padding="lg">
                @yield('content')
            </x-ui.card>
        </div>
    </div>

    @include('partials.toast')
</body>
</html>
