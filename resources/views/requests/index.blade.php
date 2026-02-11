<x-layouts.dashboard :title="__('Requests')">

    <div class="py-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Request Management</h1>
                <p class="text-gray-600 mt-1">Track and manage all your requests in one place</p>
            </div>
            <a href="{{ route('requests.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create New Request
            </a>
        </div>

        <!-- Stats Grid -->


        <!-- Main Card -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <div class="px-6 pt-4 flex flex-wrap gap-4">
                    <button
                        class="tab-button active px-4 py-3 font-medium text-sm rounded-lg transition duration-200 flex items-center gap-2"
                        data-tab="my-requests">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        My Requests
                        <span
                            class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full">{{ $myRequests->count() }}</span>
                    </button>
                    <button
                        class="tab-button px-4 py-3 font-medium text-sm rounded-lg transition duration-200 flex items-center gap-2"
                        data-tab="in-progress">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        In Progress
                        <span
                            class="bg-amber-100 text-amber-800 text-xs font-medium px-2 py-0.5 rounded-full">{{ $inProgressRequests->count() }}</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- My Requests Tab -->
                <div class="tab-content active" id="my-requests">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Draft -->
                        <button type="button" data-filter="draft"
                            class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition duration-200 hover:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 cursor-pointer summary-card {{ request('status') == 'draft' ? 'border-blue-500 ring-2 ring-blue-500 ring-opacity-30' : '' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Draft</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['draft'] }}</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3 text-xs text-gray-500">Requests in draft status</div>
                        </button>

                        <!-- Pending Approval -->
                        <button type="button" data-filter="pending"
                            class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition duration-200 hover:border-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-opacity-50 cursor-pointer summary-card {{ in_array(request('status'), ['submitted', 'junior_approved', 'senior_approved', 'payment_processing', 'on_hold']) ? 'border-amber-500 ring-2 ring-amber-500 ring-opacity-30' : '' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['pending'] }}</p>
                                </div>
                                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3 text-xs text-gray-500">Awaiting review & approval</div>
                        </button>

                        <!-- Approved -->
                        <button type="button" data-filter="approved"
                            class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition duration-200 hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 cursor-pointer summary-card {{ in_array(request('status'), ['manager_approved', 'paid']) ? 'border-emerald-500 ring-2 ring-emerald-500 ring-opacity-30' : '' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Approved</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['approved'] }}</p>
                                </div>
                                <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3 text-xs text-gray-500">Successfully approved requests</div>
                        </button>

                        <!-- Rejected -->
                        <button type="button" data-filter="rejected"
                            class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition duration-200 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 cursor-pointer summary-card {{ in_array(request('status'), ['junior_rejected', 'senior_rejected', 'manager_rejected']) ? 'border-red-500 ring-2 ring-red-500 ring-opacity-30' : '' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['rejected'] }}</p>
                                </div>
                                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3 text-xs text-gray-500">Requests that were rejected</div>
                        </button>
                    </div>

                    <!-- Filter Status Indicator -->
                    @if (request()->has('status'))
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-900">
                                        Filtering by:
                                        @php
                                            $filterLabels = [
                                                'draft' => 'Draft',
                                                'pending' => 'Pending Approval',
                                                'approved' => 'Approved',
                                                'rejected' => 'Rejected',
                                            ];
                                        @endphp
                                        {{ $filterLabels[request('status')] ?? ucfirst(request('status')) }}
                                    </span>
                                </div>
                                <a href="{{ request()->url() }}"
                                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                    Clear filter
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($myRequests->isEmpty())
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No requests yet</h3>
                            <p class="text-gray-600 mb-6">Start by creating your first request</p>
                            <a href="{{ route('requests.create') }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Request
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Request #</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Details</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Priority</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Timeline</th>
                                        <th
                                            class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($myRequests as $request)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="py-4 px-4">
                                                <div class="font-mono text-sm font-medium text-gray-900">
                                                    {{ $request->request_number }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="font-medium text-gray-900">{{ $request->title }}</div>
                                                <div class="text-sm text-gray-500 mt-1 truncate max-w-xs">
                                                    {{ Str::limit($request->description, 60) }}</div>
                                                <div class="mt-2">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ str_replace('_', ' ', $request->type) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                @php
                                                    $statusColors = [
                                                        'draft' => 'gray',
                                                        'submitted' => 'blue',
                                                        'junior_approved' => 'indigo',
                                                        'senior_approved' => 'indigo',
                                                        'manager_approved' => 'green',
                                                        'junior_rejected' => 'red',
                                                        'senior_rejected' => 'red',
                                                        'manager_rejected' => 'red',
                                                        'payment_processing' => 'amber',
                                                        'paid' => 'emerald',
                                                        'cancelled' => 'gray',
                                                        'on_hold' => 'amber',
                                                    ];
                                                    $statusColor = $statusColors[$request->status] ?? 'gray';
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                                    {{ str_replace('_', ' ', $request->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'green',
                                                        'medium' => 'amber',
                                                        'high' => 'red',
                                                        'urgent' => 'red',
                                                    ];
                                                    $priorityIcon = [
                                                        'low' => '↓',
                                                        'medium' => '→',
                                                        'high' => '↑',
                                                        'urgent' => '❗',
                                                    ];
                                                @endphp
                                                <span
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-{{ $priorityColors[$request->priority] }}-100 text-{{ $priorityColors[$request->priority] }}-800">
                                                    {{ $priorityIcon[$request->priority] }}
                                                    {{ ucfirst($request->priority) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if ($request->amount)
                                                    <div class="font-medium text-gray-900">
                                                        {{ number_format($request->amount, 2) }}
                                                        {{ $request->currency }}</div>
                                                @else
                                                    <span class="text-gray-400 text-sm">—</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="space-y-1">
                                                    <div class="text-sm text-gray-500">Created:
                                                        {{ $request->created_at->format('M d, Y') }}</div>
                                                    @if ($request->required_by_date)
                                                        @php
                                                            $now = now();
                                                            $requiredDate = \Carbon\Carbon::parse(
                                                                $request->required_by_date,
                                                            );
                                                            $diff = $now->diffInDays($requiredDate, false);

                                                            if ($diff < 0) {
                                                                $badgeClass = 'bg-red-100 text-red-800';
                                                                $text = 'Overdue';
                                                            } elseif ($diff <= 3) {
                                                                $badgeClass = 'bg-amber-100 text-amber-800';
                                                                $text = 'Due soon';
                                                            } else {
                                                                $badgeClass = 'bg-green-100 text-green-800';
                                                                $text = 'On track';
                                                            }
                                                        @endphp
                                                        <div class="text-sm">
                                                            <span
                                                                class="{{ $badgeClass }} text-xs px-2 py-0.5 rounded-full">{{ $text }}</span>
                                                            <span
                                                                class="text-gray-500 ml-2">{{ $request->required_by_date->format('M d') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('requests.show', $request) }}"
                                                        class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200"
                                                        title="View">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    @if ($request->status === 'draft')
                                                        <a href="{{ route('requests.edit', $request) }}"
                                                            class="p-2 text-gray-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition duration-200"
                                                            title="Edit">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const summaryCards = document.querySelectorAll('.summary-card');

                        summaryCards.forEach(card => {
                            card.addEventListener('click', function() {
                                const filterType = this.getAttribute('data-filter');
                                const url = new URL(window.location.href);

                                // Remove existing status parameter
                                url.searchParams.delete('status');

                                // Add new filter parameter
                                if (filterType) {
                                    url.searchParams.set('status', filterType);
                                }

                                // Reload the page with new filter
                                window.location.href = url.toString();
                            });
                        });

                        // Add keyboard navigation support
                        summaryCards.forEach(card => {
                            card.addEventListener('keypress', function(e) {
                                if (e.key === 'Enter' || e.key === ' ') {
                                    e.preventDefault();
                                    this.click();
                                }
                            });
                        });
                    });
                </script>

                <!-- In Progress Tab -->
                <div class="tab-content hidden" id="in-progress">
                    @if ($inProgressRequests->isEmpty())
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">All caught up!</h3>
                            <p class="text-gray-600">No pending requests in progress</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            <!-- Quick Stats with Filtering -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="quick-stats">
                                <!-- High Priority Filter -->
                                <button onclick="filterRequests('high_priority')" data-filter="high_priority"
                                    data-count="{{ $inProgressRequests->whereIn('priority', ['high', 'urgent'])->count() }}"
                                    class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 text-left transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 quick-stat-btn">
                                    <div class="text-2xl font-bold text-red-600">
                                        {{ $inProgressRequests->whereIn('priority', ['high', 'urgent'])->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1 flex items-center justify-between">
                                        <span>High Priority</span>
                                        <svg class="w-4 h-4 text-gray-400 filter-icon" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                            </path>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Near Deadline Filter -->
                                <button onclick="filterRequests('near_deadline')" data-filter="near_deadline"
                                    data-count="{{ $inProgressRequests->filter(function ($req) {
                                            if (!$req->required_by_date) {
                                                return false;
                                            }
                                            return now()->diffInDays($req->required_by_date, false) <= 3;
                                        })->count() }}"
                                    class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 text-left transition duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-opacity-50 quick-stat-btn">
                                    <div class="text-2xl font-bold text-amber-600">
                                        {{ $inProgressRequests->filter(function ($req) {
                                                if (!$req->required_by_date) {
                                                    return false;
                                                }
                                                return now()->diffInDays($req->required_by_date, false) <= 3;
                                            })->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1 flex items-center justify-between">
                                        <span>Near Deadline</span>
                                        <svg class="w-4 h-4 text-gray-400 filter-icon" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                            </path>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Waiting Approval Filter -->
                                <button onclick="filterRequests('waiting_approval')" data-filter="waiting_approval"
                                    data-count="{{ $inProgressRequests->whereIn('status', ['submitted', 'junior_approved', 'senior_approved'])->count() }}"
                                    class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 text-left transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 quick-stat-btn">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $inProgressRequests->whereIn('status', ['submitted', 'junior_approved', 'senior_approved'])->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1 flex items-center justify-between">
                                        <span>Waiting Approval</span>
                                        <svg class="w-4 h-4 text-gray-400 filter-icon" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                            </path>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Ready for Payment Filter -->
                                <button onclick="filterRequests('payment_ready')" data-filter="payment_ready"
                                    data-count="{{ $inProgressRequests->where('status', 'payment_processing')->count() }}"
                                    class="bg-gray-50 hover:bg-gray-100 rounded-lg p-4 text-left transition duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50 quick-stat-btn">
                                    <div class="text-2xl font-bold text-emerald-600">
                                        {{ $inProgressRequests->where('status', 'payment_processing')->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1 flex items-center justify-between">
                                        <span>Ready for Payment</span>
                                        <svg class="w-4 h-4 text-gray-400 filter-icon" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                            </path>
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <!-- Active Filter Display & Clear Button -->
                            <div class="flex items-center justify-between">
                                <div id="active-filter-display" class="flex items-center space-x-2 min-h-8">
                                    <!-- Active filter will be displayed here -->
                                </div>
                                <button onclick="clearFilter()" id="clear-filter-btn"
                                    class="hidden items-center px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear Filter
                                </button>
                            </div>

                            <!-- Requests Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full" id="requests-table">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th
                                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Request #</th>
                                            <th
                                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Requester</th>
                                            <th
                                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Details</th>
                                            <th
                                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status & Action</th>
                                            <th
                                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Timeline</th>
                                            <th
                                                class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200" id="requests-tbody">
                                        @foreach ($inProgressRequests as $request)
                                            <tr class="hover:bg-gray-50 transition duration-150 request-row"
                                                data-high-priority="{{ in_array($request->priority, ['high', 'urgent']) ? 'yes' : 'no' }}"
                                                data-near-deadline="{{ $request->required_by_date && now()->diffInDays($request->required_by_date, false) <= 3 ? 'yes' : 'no' }}"
                                                data-waiting-approval="{{ in_array($request->status, ['submitted', 'junior_approved', 'senior_approved']) ? 'yes' : 'no' }}"
                                                data-payment-ready="{{ $request->status == 'payment_processing' ? 'yes' : 'no' }}">
                                                <td class="py-4 px-4">
                                                    <div class="font-mono text-sm font-medium text-gray-900">
                                                        {{ $request->request_number }}</div>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <div class="font-medium text-gray-900">
                                                        {{ App\Models\User::where('id', $request->user_id)->first()->name ?? 'Unknown User'}}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{App\Models\User::where('id', $request->user_id)->first()->email ?? 'Unknown Email'}}</div>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <div class="font-medium text-gray-900">{{ $request->title }}</div>
                                                    <div class="flex items-center gap-2 mt-2">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ str_replace('_', ' ', $request->type) }}
                                                        </span>
                                                        @if ($request->amount)
                                                            <span class="text-sm text-gray-600">
                                                                {{ number_format($request->amount, 2) }}
                                                                {{ $request->currency }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4">
                                                    @php
                                                        $statusInfo = [
                                                            'draft' => [
                                                                'color' => 'gray',
                                                                'next' => 'Submit for approval',
                                                                'icon' => '📝',
                                                            ],
                                                            'submitted' => [
                                                                'color' => 'blue',
                                                                'next' => 'Junior Approval',
                                                                'icon' => '⏳',
                                                            ],
                                                            'junior_approved' => [
                                                                'color' => 'indigo',
                                                                'next' => 'Senior Approval',
                                                                'icon' => '👤',
                                                            ],
                                                            'senior_approved' => [
                                                                'color' => 'indigo',
                                                                'next' => 'Manager Approval',
                                                                'icon' => '👨‍💼',
                                                            ],
                                                            'payment_processing' => [
                                                                'color' => 'amber',
                                                                'next' => 'Process Payment',
                                                                'icon' => '💳',
                                                            ],
                                                        ];
                                                        $info = $statusInfo[$request->status] ?? [
                                                            'color' => 'gray',
                                                            'next' => 'Review',
                                                            'icon' => '📋',
                                                        ];
                                                    @endphp
                                                    <div class="space-y-2">
                                                        <span
                                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-{{ $info['color'] }}-100 text-{{ $info['color'] }}-800">
                                                            {{ $info['icon'] }}
                                                            {{ str_replace('_', ' ', $request->status) }}
                                                        </span>
                                                        <div class="text-xs text-gray-500">{{ $info['next'] }}</div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <div class="space-y-2">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-xs text-gray-500">Open:</span>
                                                            @php
                                                                $daysOpen = $request->created_at->diffInDays(now());
                                                                if ($daysOpen > $request->sla_days) {
                                                                    $badgeClass = 'bg-red-100 text-red-800';
                                                                } elseif ($daysOpen > $request->sla_days - 3) {
                                                                    $badgeClass = 'bg-amber-100 text-amber-800';
                                                                } else {
                                                                    $badgeClass = 'bg-green-100 text-green-800';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="text-xs font-medium {{ $badgeClass }} px-2 py-0.5 rounded-full">{{ $daysOpen }}
                                                                days</span>
                                                        </div>
                                                        @if ($request->required_by_date)
                                                            <div class="flex items-center justify-between">
                                                                <span class="text-xs text-gray-500">Deadline:</span>
                                                                <span
                                                                    class="text-xs font-medium">{{ $request->required_by_date->format('M d') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <div class="flex flex-col space-y-2">
                                                        <!-- View Button -->
                                                        <a href="{{ route('requests.show', $request) }}"
                                                            class="inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-lg transition duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                            View
                                                        </a>

                                                        <!-- Action Buttons Row -->
                                                        @php
                                                            $user = auth()->user();

                                                            // Resolve user role (single-role assumption)
                                                            $userRole = $user?->roles->first()?->name;

                                                            // Define who can see buttons
                                                            $authorizedRoles = [
                                                                'Admin',
                                                                'Super Admin',
                                                                'Junior Finance',
                                                                'Senior Finance',
                                                                'Manager',
                                                            ];

                                                            $canSeeButtons =
                                                                $userRole && in_array($userRole, $authorizedRoles);

                                                            // Default permissions
                                                            $canApprove = false;
                                                            $canReject = false;
                                                            $canRevert = false;

                                                            // Determine button permissions based on role and request status
                                                            if ($canSeeButtons) {
                                                                switch ($userRole) {
                                                                    case 'Admin':
                                                                    case 'Super Admin':
                                                                        // Admins can do everything
                                                                        $canApprove = true;
                                                                        $canReject = true;
                                                                        $canRevert = true;
                                                                        break;

                                                                    case 'Manager':
                                                                        // Manager can act when senior has approved
                                                                        $canApprove =
                                                                            $request->status === 'senior_approved';
                                                                        $canReject =
                                                                            $request->status === 'senior_approved';
                                                                        $canRevert =
                                                                            $request->status === 'senior_approved';
                                                                        break;

                                                                    case 'Senior Finance':
                                                                        // Senior finance acts after manager approval
                                                                        $canApprove =
                                                                            $request->status === 'manager_approved';
                                                                        $canReject =
                                                                            $request->status === 'manager_approved';
                                                                        $canRevert =
                                                                            $request->status === 'manager_approved';
                                                                        break;

                                                                    case 'Junior Finance':
                                                                        // Junior finance cannot act
                                                                        $canApprove = false;
                                                                        $canReject = false;
                                                                        $canRevert = false;
                                                                        break;
                                                                }
                                                            }
                                                        @endphp

                                                        @if ($canSeeButtons)
                                                            <div class="flex space-x-2">

                                                                @if ($canApprove && !in_array($request->status, ['rejected', 'payment_processing']))
                                                                    <form
                                                                        action="{{ route('requests.approve', $request->id) }}"
                                                                        method="POST" class="flex-1">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="w-full inline-flex items-center justify-center gap-1 px-2 py-1.5 bg-green-50 hover:bg-green-100 text-green-700 text-xs font-medium rounded-lg"
                                                                            onclick="return confirm('Approve this request?')">
                                                                            ✓ <span
                                                                                class="hidden sm:inline">Approve</span>
                                                                        </button>
                                                                    </form>
                                                                @endif

                                                                @if ($canReject && $request->status !== 'rejected')
                                                                    <form
                                                                        action="{{ route('requests.reject', $request) }}"
                                                                        method="POST" class="flex items-start gap-1">
                                                                        @csrf
                                                                        <button type="button"
                                                                            onclick="openRejectModal('{{ route('requests.reject', $request) }}')"
                                                                            class=" text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition duration-200"
                                                                            title="Reject">
                                                                            X <span
                                                                                class="hidden sm:inline">Reject</span>

                                                                        </button>
                                                                    </form>
                                                                @endif

                                                                @if ($canRevert && !in_array($request->status, ['draft', 'rejected']))
                                                                    <form
                                                                        action="{{ route('requests.revert', $request) }}"
                                                                        method="POST" class="flex-1">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="w-full inline-flex items-center justify-center gap-1 px-2 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-medium rounded-lg"
                                                                            onclick="return confirm('Revert this request?')">
                                                                            ↶ <span
                                                                                class="hidden sm:inline">Revert</span>
                                                                        </button>
                                                                    </form>
                                                                @endif

                                                                @if (!($canApprove || $canReject || $canRevert))
                                                                    <div
                                                                        class="text-xs text-gray-500 italic px-2 py-1.5">
                                                                        No actions available for your role
                                                                    </div>
                                                                @endif

                                                            </div>
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                {{-- Reject openRejectModal --}}
                <!-- Reject Modal -->
                <div id="rejectModal"
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Reject Request</h3>

                            <form id="rejectForm" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="rejectionReason" class="block text-sm font-medium text-gray-700 mb-1">
                                        Reason for rejection <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="rejectionReason" name="rejection_reason" rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm"
                                        placeholder="Please provide a reason for rejecting this request..." required></textarea>
                                    <p class="mt-1 text-xs text-gray-500">This reason will be visible to the requester.
                                    </p>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                                    <button type="button" onclick="closeRejectModal()"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Confirm Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    let currentRejectUrl = '';

                    function openRejectModal(url) {
                        currentRejectUrl = url;
                        const modal = document.getElementById('rejectModal');
                        modal.classList.remove('hidden');
                        document.getElementById('rejectForm').action = url;
                        document.getElementById('rejectReason').focus();
                    }

                    function closeRejectModal() {
                        const modal = document.getElementById('rejectModal');
                        modal.classList.add('hidden');
                        document.getElementById('rejectReason').value = '';
                    }

                    // Close modal when clicking outside
                    document.getElementById('rejectModal').addEventListener('click', function(e) {
                        if (e.target.id === 'rejectModal') {
                            closeRejectModal();
                        }
                    });

                    // Close modal with Escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            closeRejectModal();
                        }
                    });

                    // Form submission
                    document.getElementById('rejectForm').addEventListener('submit', function(e) {
                        const reason = document.getElementById('rejectReason').value.trim();
                        if (!reason) {
                            e.preventDefault();
                            alert('Please provide a reason for rejection.');
                            document.getElementById('rejectReason').focus();
                        }
                    });
                </script>
                <!-- JavaScript for Filtering -->
                <script>
                    let currentFilter = null;
                    const filterLabels = {
                        'high_priority': 'High Priority',
                        'near_deadline': 'Near Deadline',
                        'waiting_approval': 'Waiting Approval',
                        'payment_ready': 'Ready for Payment'
                    };

                    const filterColors = {
                        'high_priority': 'red',
                        'near_deadline': 'amber',
                        'waiting_approval': 'blue',
                        'payment_ready': 'emerald'
                    };

                    function filterRequests(filterType) {
                        // Remove active class from all filter buttons
                        document.querySelectorAll('.quick-stat-btn').forEach(btn => {
                            btn.classList.remove('bg-gray-200', 'ring-2');
                            btn.classList.add('bg-gray-50');
                        });

                        // Add active class to clicked filter button
                        const activeBtn = document.querySelector(`[data-filter="${filterType}"]`);
                        if (activeBtn) {
                            activeBtn.classList.remove('bg-gray-50');
                            activeBtn.classList.add('bg-gray-200', `ring-2`, `ring-${filterColors[filterType]}-500`, `ring-opacity-50`);
                        }

                        // If clicking the same filter, clear it
                        if (currentFilter === filterType) {
                            clearFilter();
                            return;
                        }

                        currentFilter = filterType;

                        // Show filtered rows
                        const rows = document.querySelectorAll('.request-row');
                        let visibleCount = 0;

                        rows.forEach(row => {
                            const shouldShow = row.getAttribute(`data-${filterType.replace('_', '-')}`) === 'yes';
                            row.style.display = shouldShow ? '' : 'none';
                            if (shouldShow) visibleCount++;
                        });

                        // Update active filter display
                        const filterDisplay = document.getElementById('active-filter-display');
                        filterDisplay.innerHTML = `
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-${filterColors[filterType]}-100 text-${filterColors[filterType]}-800">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            ${filterLabels[filterType]} (${visibleCount})
        </span>
    `;

                        // Show clear button
                        document.getElementById('clear-filter-btn').classList.remove('hidden');

                        // Update table footer with count
                        updateTableFooter(visibleCount, rows.length);
                    }

                    function clearFilter() {
                        currentFilter = null;

                        // Reset all filter buttons
                        document.querySelectorAll('.quick-stat-btn').forEach(btn => {
                            btn.classList.remove('bg-gray-200', 'ring-2');
                            btn.classList.add('bg-gray-50');
                        });

                        // Show all rows
                        const rows = document.querySelectorAll('.request-row');
                        rows.forEach(row => {
                            row.style.display = '';
                        });

                        // Clear active filter display
                        document.getElementById('active-filter-display').innerHTML = '';
                        document.getElementById('clear-filter-btn').classList.add('hidden');

                        // Reset table footer
                        updateTableFooter(rows.length, rows.length);
                    }

                    function updateTableFooter(visible, total) {
                        let footer = document.querySelector('#requests-table tfoot');
                        if (!footer) {
                            const table = document.querySelector('#requests-table');
                            const tbody = document.querySelector('#requests-tbody');
                            footer = document.createElement('tfoot');
                            table.appendChild(footer);
                        }

                        footer.innerHTML = `
        <tr class="bg-gray-50 border-t border-gray-200">
            <td colspan="6" class="py-3 px-4 text-sm text-gray-500">
                Showing ${visible} of ${total} requests
                ${visible < total ? `(filtered by ${filterLabels[currentFilter]})` : ''}
            </td>
        </tr>
    `;
                    }

                    // Initialize with all rows visible
                    document.addEventListener('DOMContentLoaded', function() {
                        const rows = document.querySelectorAll('.request-row');
                        updateTableFooter(rows.length, rows.length);
                    });
                </script>

                <!-- Optional CSS for better hover states -->
                <style>
                    .quick-stat-btn:hover .filter-icon {
                        transform: scale(1.1);
                        transition: transform 0.2s ease;
                    }

                    .quick-stat-btn:active {
                        transform: scale(0.98);
                        transition: transform 0.1s ease;
                    }

                    .request-row {
                        transition: all 0.3s ease;
                    }
                </style>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .tab-button {
                position: relative;
                color: #6b7280;
                background: transparent;
            }

            .tab-button.active {
                color: #1f2937;
                background: #f3f4f6;
            }

            .tab-button.active::after {
                content: '';
                position: absolute;
                bottom: -1px;
                left: 0;
                right: 0;
                height: 2px;
                background: #3b82f6;
            }

            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
                animation: fadeIn 0.3s ease;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Custom scrollbar */
            .overflow-x-auto::-webkit-scrollbar {
                height: 6px;
            }

            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #d1d5db;
                border-radius: 3px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #9ca3af;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tab Switching
                const tabButtons = document.querySelectorAll('.tab-button');
                const tabContents = document.querySelectorAll('.tab-content');

                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const tabId = this.getAttribute('data-tab');

                        // Update active button
                        tabButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');

                        // Show active content
                        tabContents.forEach(content => {
                            content.classList.remove('active');
                            content.classList.add('hidden');
                        });

                        const activeContent = document.getElementById(tabId);
                        activeContent.classList.remove('hidden');
                        activeContent.classList.add('active');

                        // Save to localStorage
                        localStorage.setItem('requestsActiveTab', tabId);
                    });
                });

                // Restore active tab
                const storedTab = localStorage.getItem('requestsActiveTab');
                if (storedTab) {
                    const tabButton = document.querySelector(`[data-tab="${storedTab}"]`);
                    if (tabButton) {
                        tabButton.click();
                    }
                }

                // Add search functionality
                const header = document.querySelector('.border-b.border-gray-200');
                const searchContainer = document.createElement('div');
                searchContainer.className = 'px-6 pb-4';
                searchContainer.innerHTML = `
            <div class="relative max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="requestSearch" class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search requests...">
            </div>
        `;

                header.parentNode.insertBefore(searchContainer, header.nextSibling);

                const searchInput = document.getElementById('requestSearch');
                searchInput.addEventListener('keyup', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const activeTable = document.querySelector('.tab-content.active table tbody');

                    if (activeTable) {
                        const rows = activeTable.getElementsByTagName('tr');

                        Array.from(rows).forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchTerm) ? '' : 'none';
                        });
                    }
                });
            });
        </script>
    @endpush
</x-layouts.dashboard>
