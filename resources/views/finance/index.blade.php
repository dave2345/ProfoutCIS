<x-layouts.dashboard>
    <div class="p-6" x-data="{ activeTab: 'paid' }">

        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-2">Finance Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">
                Welcome to the Finance section. Here you can manage all financial transactions and reports.
            </p>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button
                    @click="activeTab = 'paid'"
                    :class="activeTab === 'paid'
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    Paid
                </button>

                <button
                    @click="activeTab = 'approved'"
                    :class="activeTab === 'approved'
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    Awaiting Payment
                </button>

                <button
                    @click="activeTab = 'rejected'"
                    :class="activeTab === 'rejected'
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    Rejected
                </button>

                <button
                    @click="activeTab = 'report'"
                    :class="activeTab === 'report'
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    Report
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div>
            <div x-show="activeTab === 'paid'" x-cloak>
                @livewire('finance.paid-requests')
            </div>

            <div x-show="activeTab === 'approved'" x-cloak>
                @livewire('finance.approved-requests')
            </div>

            <div x-show="activeTab === 'rejected'" x-cloak>
                @livewire('finance.rejected-requests')
            </div>

            <div x-show="activeTab === 'report'" x-cloak>
                @livewire('finance.request-report')
            </div>
        </div>

    </div>
</x-layouts.dashboard>
