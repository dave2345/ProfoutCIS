
<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Generate Report</h2>

        <div class="space-y-6">
            <!-- Report Type Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Report Period
                </label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="reportType" value="day" class="form-radio text-blue-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Today</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="reportType" value="week" class="form-radio text-blue-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">This Week</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="reportType" value="month" class="form-radio text-blue-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">This Month</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="reportType" value="year" class="form-radio text-blue-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">This Year</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="reportType" value="custom" class="form-radio text-blue-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Custom Range</span>
                    </label>
                </div>
            </div>

            <!-- Custom Date Range -->
            @if($reportType === 'custom')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Date
                        </label>
                        <input
                            type="date"
                            wire:model.live="customStartDate"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Date
                        </label>
                        <input
                            type="date"
                            wire:model.live="customEndDate"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        >
                    </div>
                </div>
            @endif

            <!-- User Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Users
                </label>
                <div class="mb-2">
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            wire:model.live="selectAll"
                            class="form-checkbox text-blue-600 rounded"
                        >
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Select All</span>
                    </label>
                </div>
                <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-2">
                    @foreach($users as $user)
                        <label class="flex items-center p-2 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <input
                                type="checkbox"
                                wire:model.live="selectedUsers"
                                value="{{ $user->id }}"
                                class="form-checkbox text-blue-600 rounded"
                            >
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $user->name }} ({{ $user->email }})
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Generate Report Button -->
            <div class="flex justify-end space-x-4">
                <button
                    wire:click="generateReport"
                    class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Generate Report
                </button>
            </div>
        </div>

        <!-- Report Results -->
        @if($reportData)
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Report Results</h3>
                    <div class="space-x-2">
                        <button
                            wire:click="exportReport('pdf')"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                        >
                            Export PDF
                        </button>
                        <button
                            wire:click="exportReport('excel')"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Export Excel
                        </button>
                    </div>
                </div>

                <!-- Report Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <div class="text-sm text-blue-600 dark:text-blue-400 mb-1">Total Requests</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reportData['total_requests'] }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <div class="text-sm text-green-600 dark:text-green-400 mb-1">Total Amount</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($reportData['total_amount'], 2) }}</div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                        <div class="text-sm text-yellow-600 dark:text-yellow-400 mb-1">Approved</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reportData['by_status']['approved'] }}</div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                        <div class="text-sm text-purple-600 dark:text-purple-400 mb-1">Paid</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reportData['by_status']['paid'] }}</div>
                    </div>
                </div>

                <!-- Status Breakdown -->
                <div class="mb-6">
                    <h4 class="text-md font-medium mb-2">Status Breakdown</h4>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Pending:</span>
                            <span class="font-semibold">{{ $reportData['by_status']['pending'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Approved:</span>
                            <span class="font-semibold">{{ $reportData['by_status']['approved'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Paid:</span>
                            <span class="font-semibold">{{ $reportData['by_status']['paid'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Rejected:</span>
                            <span class="font-semibold">{{ $reportData['by_status']['rejected'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Requests Table -->
                <div class="overflow-x-auto">
                    <h4 class="text-md font-medium mb-2">Request Details</h4>
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Request #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($reportData['requests'] as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $request->request_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        ${{ number_format($request->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($request->status === 'paid') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            @elseif($request->status === 'approved') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                            @elseif($request->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
