<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
    <style>
        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-zinc-900 dark:to-zinc-800 flex">

    <!-- Mobile Overlay -->
    <div class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30 hidden" id="mobile-overlay"></div>

    <!-- Sidebar -->
    <aside class="fixed lg:sticky top-0 left-0 h-screen w-64 lg:w-72 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl border-r border-gray-200/50 dark:border-zinc-700/50 flex flex-col shadow-xl lg:shadow-lg -translate-x-full lg:translate-x-0 z-40 transition-transform duration-300">

        <!-- Navigation -->
        <nav class="flex-1 mt-15 px-4 py-2 space-y-1 overflow-y-auto">
            <!-- Platform -->
            <div class="px-3 py-2">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Platform') }}</h3>
            </div>

            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 border border-orange-200 dark:border-orange-800/30 text-orange-600 dark:text-orange-400' : 'text-gray-700 dark:text-gray-300' }}">
                <div class="p-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gradient-to-br from-orange-500 to-amber-500 text-white' : 'bg-gray-100 dark:bg-zinc-800 group-hover:bg-orange-100 dark:group-hover:bg-orange-900/20 text-gray-600 dark:text-gray-400 group-hover:text-orange-600 dark:group-hover:text-orange-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Dashboard') }}</span>
                {!! request()->routeIs('dashboard') ? '<div class="ml-auto w-2 h-2 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 animate-pulse"></div>' : '' !!}
            </a>

            <!-- Business Management -->
            <div class="px-3 py-2 mt-6">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Business Management') }}</h3>
            </div>

            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all {{ request()->routeIs('projects.*') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800/30 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }}">
                <div class="p-2 rounded-lg {{ request()->routeIs('projects.*') ? 'bg-gradient-to-br from-blue-500 to-indigo-500 text-white' : 'bg-gray-100 dark:bg-zinc-800 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/20 text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Projects') }}</span>
                <span class="ml-auto px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full">12</span>
            </a>

            <a href="{{ route('tenders.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all {{ request()->routeIs('tenders.*') ? 'bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-200 dark:border-emerald-800/30 text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300' }}">
                <div class="p-2 rounded-lg {{ request()->routeIs('tenders.*') ? 'bg-gradient-to-br from-emerald-500 to-teal-500 text-white' : 'bg-gray-100 dark:bg-zinc-800 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/20 text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Tenders') }}</span>
                <span class="ml-auto px-2 py-1 text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full">5</span>
            </a>

            <a href="{{ route('requests.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all {{ request()->routeIs('requests.*') ? 'bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 border border-purple-200 dark:border-purple-800/30 text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300' }}">
                <div class="p-2 rounded-lg {{ request()->routeIs('requests.*') ? 'bg-gradient-to-br from-purple-500 to-violet-500 text-white' : 'bg-gray-100 dark:bg-zinc-800 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/20 text-gray-600 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Requests') }}</span>
                <span class="ml-auto px-2 py-1 text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-full">8</span>
            </a>

            <a href="{{ route('certificates.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all {{ request()->routeIs('certificates.*') ? 'bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 border border-amber-200 dark:border-amber-800/30 text-amber-600 dark:text-amber-400' : 'text-gray-700 dark:text-gray-300' }}">
                <div class="p-2 rounded-lg {{ request()->routeIs('certificates.*') ? 'bg-gradient-to-br from-amber-500 to-yellow-500 text-white' : 'bg-gray-100 dark:bg-zinc-800 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/20 text-gray-600 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Certificates') }}</span>
                <span class="ml-auto px-2 py-1 text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full">3</span>
            </a>

             <a href="{{ route('finance.index') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all {{ request()->routeIs('finance.*') ? 'bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 border border-amber-200 dark:border-amber-800/30 text-amber-600 dark:text-amber-400' : 'text-gray-700 dark:text-gray-300' }}">
                <div class="p-2 rounded-lg {{ request()->routeIs('finance.*') ? 'bg-gradient-to-br from-amber-500 to-yellow-500 text-white' : 'bg-gray-100 dark:bg-zinc-800 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/20 text-gray-600 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Finance') }}</span>
                <span class="ml-auto px-2 py-1 text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full">3</span>
            </a>
            <hr class="border-gray-200/50 dark:border-zinc-700/50 mx-4 my-4">

            <!-- Additional Links -->
            <a href="{{ route('settings') }}" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all {{ request()->routeIs('settings') ? 'bg-gradient-to-r from-gray-50 to-gray-100 dark:from-zinc-800 dark:to-zinc-700/50 border border-gray-200 dark:border-zinc-700 text-gray-800 dark:text-gray-200' : 'text-gray-700 dark:text-gray-300' }}">
                <div class="p-2 rounded-lg {{ request()->routeIs('settings') ? 'bg-gradient-to-br from-gray-600 to-gray-700 text-white' : 'bg-gray-100 dark:bg-zinc-800 group-hover:bg-gray-200 dark:group-hover:bg-zinc-700 text-gray-600 dark:text-gray-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Settings') }}</span>
            </a>

            <!-- External Links -->
            <div class="px-3 py-2 mt-6">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Resources') }}</h3>
            </div>

            <a href="https://github.com/laravel/livewire-starter-kit" target="_blank" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all text-gray-700 dark:text-gray-300">
                <div class="p-2 rounded-lg bg-gray-100 dark:bg-zinc-800 group-hover:bg-gray-200 dark:group-hover:bg-zinc-700 text-gray-600 dark:text-gray-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Repository') }}</span>
                <svg class="w-4 h-4 ml-auto text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>

            <a href="https://laravel.com/docs/starter-kits#livewire" target="_blank" class="flex items-center gap-3 px-4 py-3 mx-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800/50 group transition-all text-gray-700 dark:text-gray-300">
                <div class="p-2 rounded-lg bg-gray-100 dark:bg-zinc-800 group-hover:bg-gray-200 dark:group-hover:bg-zinc-700 text-gray-600 dark:text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="font-medium">{{ __('Documentation') }}</span>
                <svg class="w-4 h-4 ml-auto text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-gray-200/50 dark:border-zinc-700/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-gradient-to-r from-gray-100 to-gray-200 dark:from-zinc-800 dark:to-zinc-700 hover:from-gray-200 hover:to-gray-300 dark:hover:from-zinc-700 dark:hover:to-zinc-600 text-gray-700 dark:text-gray-300 font-medium transition-all group">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Header -->
    <header class="lg:hidden fixed top-0 left-0 right-0 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-xl border-b border-gray-200/50 dark:border-zinc-700/50 flex items-center justify-between px-5 py-4 z-30 shadow-lg">
        <div class="flex items-center gap-3">
            <button id="mobile-menu-toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-600 dark:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white font-semibold shadow-md">
                    {{ auth()->user()->initials() }}
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ auth()->user()->name }}</h2>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-600 dark:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
        </div>
    </header>

    <!-- Scripts -->
    <script>
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const sidebar = document.querySelector('aside');
        const closeBtn = document.getElementById('sidebar-close');
        const overlay = document.getElementById('mobile-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024 &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target) &&
                !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });

        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });
    </script>

</body>
</html>
