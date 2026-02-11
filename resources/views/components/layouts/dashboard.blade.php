<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel Dashboard') }}</title>

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

    <div class="flex flex-1">

        <!-- Sidebar -->
        <x-layouts.app.sidebar />

        <!-- Main area -->
        <div class="flex-1 flex flex-col">

            <!-- Header -->
            <x-layouts.app.header />

            <!-- Header spacer -->
            {{-- <div class="h-20"></div> --}}

            <!-- Page content -->
            <main class="flex-1 p-6 bg-white/80 dark:bg-zinc-900/80">
                @if(Route::currentRouteName() === 'dashboard')

                @else
                <button onclick="window.history.back()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    ← Back
                </button>
                @endif
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-gray-200 bg-white/80 dark:bg-zinc-900/80 py-4 text-center text-xs text-gray-500">
                © {{ date('Y') }} Professional Outcomes. All rights reserved.
            </footer>

        </div>
    </div>

    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')
</body>
</html>
