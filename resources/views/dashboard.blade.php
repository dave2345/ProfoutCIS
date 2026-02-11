<x-layouts.dashboard :title="__('Dashboard')">
    <!-- Dashboard Container -->
    <div class="space-y-6">

        <!-- Welcome & Quick Stats Header -->
        <div class="rounded-2xl bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 border border-orange-200 dark:border-orange-800/30 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Here's what's happening with your business today.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, F j, Y') }}</span>
                    <div class="px-3 py-1 bg-white dark:bg-zinc-800 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-zinc-700">
                        {{ now()->format('h:i A') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('projects.create') }}" class="group p-4 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-all duration-200 text-left">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-500 text-white group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">New Project</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Start a new project</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('tenders.create') }}" class="group p-4 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-all duration-200 text-left">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 text-white group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">Submit Tender</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Submit new tender</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('requests.create') }}" class="group p-4 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-all duration-200 text-left">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-purple-500 to-violet-500 text-white group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">New Request</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Create payment request</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('certificates.create') }}" class="group p-4 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-all duration-200 text-left">
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-amber-500 to-yellow-500 text-white group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">Certificates</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Manage certificates</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Livewire Statistics Cards -->
        <livewire:dashboard.dashboard-stats />

        <!-- Charts & Recent Activities Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Performance Chart -->
            <div class="lg:col-span-2">
                <livewire:dashboard.performance-chart />
            </div>

            <!-- Recent Activities -->
            <livewire:dashboard.recent-activities />
        </div>

        <!-- Upcoming Deadlines & Quick Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Upcoming Deadlines -->
            <div class="lg:col-span-2">
                <livewire:dashboard.upcoming-deadlines />
            </div>

            <!-- Quick Stats -->
            <div class="rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">Quick Stats</h3>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">P</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Active Projects</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    <livewire:dashboard.counter :value="App\Models\Project::where('status', 'in_progress')->count()" duration="1000" />
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Completion</div>
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                {{-- @php
                                    $completed = App\Models\Project::where('status', 'completed')->count();
                                    $total = App\Models\Project::count() ?: 1;
                                    echo round(($completed / $total) * 100) . '%';
                                @endphp --}}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">T</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Open Tenders</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    <livewire:dashboard.counter :value="App\Models\Tender::where('status', 'open')->count()" duration="1000" />
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Success Rate</div>
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                {{-- @php
                                    $awarded = App\Models\Tender::where('status', 'awarded')->count();
                                    $total = App\Models\Tender::count() ?: 1;
                                    echo round(($awarded / $total) * 100) . '%';
                                @endphp --}}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">R</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pending Approvals</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    {{-- <livewire:dashboard.counter :value="App\Models\Request::whereIn('status', ['submitted', 'junior_approved', 'senior_approved'])->count()" duration="1000" /> --}}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Avg. Time</div>
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">2.5 days</div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="my-6 border-t border-gray-200 dark:border-zinc-700"></div>

                <!-- Performance Indicator -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Monthly Performance</span>
                        <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">+24%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-zinc-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Auto-refresh Script -->
    <script>
        // Auto-refresh dashboard every 30 seconds
        setInterval(() => {
            Livewire.dispatch('refreshDashboardStats');
            Livewire.dispatch('refreshChart');
            Livewire.dispatch('refreshActivities');
            Livewire.dispatch('refreshDeadlines');
        }, 30000);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Start auto-refresh
            setInterval(() => {
                Livewire.dispatch('refreshDashboardStats');
            }, 30000);
        });
    </script>

</x-layouts.dashboard>
