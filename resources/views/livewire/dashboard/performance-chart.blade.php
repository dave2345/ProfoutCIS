<div class="rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Performance Overview</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($period) }} growth trends</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex bg-gray-100 dark:bg-zinc-700 rounded-lg p-1">
                <button
                    wire:click="updatePeriod('monthly')"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $period == 'monthly' ? 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300' }}">
                    Monthly
                </button>
                <button
                    wire:click="updatePeriod('quarterly')"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $period == 'quarterly' ? 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300' }}">
                    Quarterly
                </button>
                <button
                    wire:click="updatePeriod('yearly')"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $period == 'yearly' ? 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300' }}">
                    Yearly
                </button>
            </div>
            <button
                wire:click="loadData"
                class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-zinc-700"
                title="Refresh Chart"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="h-64">
        @if($chartType === 'bar')
        <div class="h-full flex items-end space-x-2 sm:space-x-4 px-2">
            @foreach($data as $item)
            @php
                $label = $period === 'monthly' ? $item['month'] : ($period === 'quarterly' ? $item['quarter'] : $item['year']);
                $projectsHeight = ($item['projects'] / $maxValue) * 80;
                $tendersHeight = ($item['tenders'] / $maxValue) * 80;
                $requestsHeight = ($item['requests'] / $maxValue) * 80;
            @endphp
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full flex space-x-1 justify-center mb-2" style="height: 80px;">
                    <div
                        class="w-1/3 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t transition-all duration-300 hover:opacity-90 cursor-pointer"
                        style="height: {{ $projectsHeight }}%"
                        title="Projects: {{ $item['projects'] }}"
                    ></div>
                    <div
                        class="w-1/3 bg-gradient-to-t from-emerald-500 to-emerald-400 rounded-t transition-all duration-300 hover:opacity-90 cursor-pointer"
                        style="height: {{ $tendersHeight }}%"
                        title="Tenders: {{ $item['tenders'] }}"
                    ></div>
                    <div
                        class="w-1/3 bg-gradient-to-t from-purple-500 to-purple-400 rounded-t transition-all duration-300 hover:opacity-90 cursor-pointer"
                        style="height: {{ $requestsHeight }}%"
                        title="Requests: {{ $item['requests'] }}"
                    ></div>
                </div>
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $label }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Chart Legend -->
        <div class="flex flex-wrap gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-gradient-to-r from-blue-500 to-blue-400 mr-2"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Projects</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400 mr-2"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Tenders</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full bg-gradient-to-r from-purple-500 to-purple-400 mr-2"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Requests</span>
            </div>
            <div class="flex items-center ml-auto">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Total: {{ array_sum(array_column($data, 'projects')) + array_sum(array_column($data, 'tenders')) + array_sum(array_column($data, 'requests')) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <div class="text-center p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                {{ array_sum(array_column($data, 'projects')) }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Projects</div>
        </div>
        <div class="text-center p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                {{ array_sum(array_column($data, 'tenders')) }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Tenders</div>
        </div>
        <div class="text-center p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20">
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                {{ array_sum(array_column($data, 'requests')) }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Requests</div>
        </div>
        <div class="text-center p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20">
            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                ${{ number_format(array_sum(array_column($data, 'revenue'))) }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Revenue</div>
        </div>
    </div>
</div>
