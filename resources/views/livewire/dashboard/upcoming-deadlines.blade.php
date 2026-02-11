<div class="rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Upcoming Deadlines</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Projects & tenders ending soon</p>
        </div>
        <div class="flex items-center space-x-2">
            <div class="flex bg-gray-100 dark:bg-zinc-700 rounded-lg p-1">
                <button
                    wire:click="updateDaysAhead(7)"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $daysAhead == 7 ? 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300' }}">
                    7 Days
                </button>
                <button
                    wire:click="updateDaysAhead(30)"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $daysAhead == 30 ? 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300' }}">
                    30 Days
                </button>
                <button
                    wire:click="updateDaysAhead(90)"
                    class="px-3 py-1.5 text-sm font-medium rounded {{ $daysAhead == 90 ? 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300' }}">
                    90 Days
                </button>
            </div>
            <button
                wire:click="refreshDeadlines"
                class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-zinc-700"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($deadlines as $deadline)
        <a
            href="{{ $deadline['link'] }}"
            class="flex items-center justify-between p-4 rounded-lg border border-gray-100 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors group"
        >
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div class="p-2 rounded-lg bg-{{ $deadline['color'] }}-100 dark:bg-{{ $deadline['color'] }}-900/30 text-{{ $deadline['color'] }}-600 dark:text-{{ $deadline['color'] }}-400">
                        @if($deadline['type'] === 'project')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        @endif
                    </div>
                    @if($deadline['days_remaining'] <= 3)
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-{{ $deadline['color'] }}-500 rounded-full animate-pulse"></div>
                    @endif
                </div>
                <div>
                    <h4 class="font-medium text-gray-800 dark:text-gray-100 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">
                        {{ $deadline['title'] }}
                    </h4>
                    <div class="flex items-center space-x-3 mt-1">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $deadline['type'] === 'project' ? 'Project' : 'Tender' }} •
                            {{ $deadline['formatted_deadline'] }}
                        </p>
                        @if($deadline['type'] === 'tender' && $deadline['estimated_value'])
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-gray-400">
                            ${{ number_format($deadline['estimated_value']) }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <div class="text-sm font-semibold text-{{ $deadline['color'] }}-600 dark:text-{{ $deadline['color'] }}-400">
                        {{ $deadline['days_remaining'] }} days
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">remaining</div>
                </div>
                <svg class="w-4 h-4 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @empty
        <div class="text-center py-8">
            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="mt-2 text-gray-500 dark:text-gray-400">No upcoming deadlines</p>
            <p class="text-sm text-gray-400 dark:text-gray-500">You're all caught up!</p>
        </div>
        @endforelse
    </div>

    @if(!empty($deadlines))
    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-zinc-700">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ count($deadlines) }} {{ Str::plural('deadline', count($deadlines)) }} in next {{ $daysAhead }} days
            </div>
            {{-- <a href="{{ route('calendar') }}" class="text-sm font-medium text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300">
                View Calendar →
            </a> --}}
        </div>
    </div>
    @endif
</div>
