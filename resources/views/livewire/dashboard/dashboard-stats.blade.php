<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total Projects -->
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full -translate-y-12 translate-x-6"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="text-sm font-medium px-2 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                    +12%
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                <livewire:dashboard.counter :value="$totalProjects" duration="1500" />
            </h3>
            <p class="text-gray-600 dark:text-gray-400">Total Projects</p>
            <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                {{ $activeProjects }} active
            </div>
        </div>
    </div>

    <!-- Total Tenders -->
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-full -translate-y-12 translate-x-6"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="text-sm font-medium px-2 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                    +8%
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                <livewire:dashboard.counter :value="$totalTenders" duration="1500" />
            </h3>
            <p class="text-gray-600 dark:text-gray-400">Total Tenders</p>
            <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 text-orange-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $openTenders }} open
            </div>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-100 to-violet-100 dark:from-purple-900/20 dark:to-violet-900/20 rounded-full -translate-y-12 translate-x-6"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500 to-violet-500 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div class="text-sm font-medium px-2 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                    +15%
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                <livewire:dashboard.counter :value="$totalRequests" duration="1500" />
            </h3>
            <p class="text-gray-600 dark:text-gray-400">Total Requests</p>
            <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 text-yellow-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                {{ $pendingApprovals }} pending
            </div>
        </div>
    </div>

    <!-- Active Certificates -->
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-amber-100 to-yellow-100 dark:from-amber-900/20 dark:to-yellow-900/20 rounded-full -translate-y-12 translate-x-6"></div>
        <div class="relative">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-xl bg-gradient-to-br from-amber-500 to-yellow-500 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="text-sm font-medium px-2 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                    +5%
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                <livewire:dashboard.counter :value="$totalCertificates" duration="1500" />
            </h3>
            <p class="text-gray-600 dark:text-gray-400">Active Certificates</p>
            <div class="mt-4 flex items-center text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $expiringCertificates }} expiring
            </div>
        </div>
    </div>
</div>
