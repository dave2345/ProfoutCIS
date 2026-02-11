<div class="rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Recent Activities</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Latest updates across platform</p>
        </div>
        <button
            wire:click="refreshActivities"
            class="text-sm font-medium text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 flex items-center space-x-1"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span>Refresh</span>
        </button>
    </div>

    <div class="space-y-4">
        @forelse($activities as $activity)
        <a
            href="{{ $activity['link'] }}"
            class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors group"
        >
            <div class="relative">
                <div class="p-2 rounded-lg bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/30 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400">
                    @if($activity['icon'] === 'folder')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    @elseif($activity['icon'] === 'shield')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    @endif
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $activity['user_initials'] }}</span>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate">{{ $activity['title'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['description'] }}</p>
                <div class="flex items-center justify-between mt-1">
                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $activity['time'] }}</p>
                    <span class="text-xs px-2 py-1 rounded-full bg-{{ $activity['color'] }}-100 dark:bg-{{ $activity['color'] }}-900/30 text-{{ $activity['color'] }}-600 dark:text-{{ $activity['color'] }}-400 capitalize">
                        {{ $activity['type'] }}
                    </span>
                </div>
            </div>
            <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        @empty
        <div class="text-center py-8">
            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="mt-2 text-gray-500 dark:text-gray-400">No recent activities</p>
        </div>
        @endforelse
    </div>

    @if(count($activities) >= $limit)
    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
        <button
            wire:click="loadMore"
            class="w-full py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700 rounded-lg transition-colors"
        >
            Load More Activities
        </button>
    </div>
    @endif
</div>
