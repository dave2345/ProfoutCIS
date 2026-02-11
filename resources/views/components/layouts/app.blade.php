<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles
</head>

<body class="min-h-screen flex flex-col bg-gray-50 font-sans text-gray-800">

    <!-- Header -->
    <header class="fixed top-0 inset-x-0 bg-gradient-to-br from-orange-500 to-orange-400 shadow z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <!-- Logo & Branding -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-white shadow flex items-center justify-center overflow-hidden">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Logo"
                        class="w-full h-full object-contain p-1"
                    >
                </div>

                <div class="leading-tight">
                    <h1 class="text-xl font-semibold text-white">
                        Professional Outcomes
                    </h1>
                    <p class="text-xs text-orange-100">
                        Achieving Excellence Together
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="rounded-md bg-white/90 px-4 py-2 text-sm font-medium text-orange-600 shadow hover:bg-white transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-white hover:underline">
                            Sign In
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="rounded-md bg-white px-4 py-2 text-sm font-medium text-orange-600 shadow hover:bg-orange-50 transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif

        </div>
    </header>

    <!-- Header spacer -->
    <div class="h-20"></div>

    <!-- Main content (grows to push footer down) -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer (sticks to bottom when content is short) -->
    <footer class="border-t border-gray-200 py-4 text-center text-xs text-gray-500">
        © {{ date('Y') }} Professional Outcomes. All rights reserved.
    </footer>

    @livewireScripts
</body>

</html>

