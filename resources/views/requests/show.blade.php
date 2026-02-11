<x-layouts.dashboard>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Request Details</h2>
                <p class="text-sm text-gray-600">Request #{{ $request->id }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('requests.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    ← Back to Requests
                </a>
                @if ($request->status === 'draft')
                    <a href="{{ route('requests.edit', $request) }}"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Edit Request
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        {{-- ========================= --}}
        {{-- REQUEST STATUS & ACTIONS --}}
        {{-- ========================= --}}
        <div class="bg-white border rounded-xl shadow-sm mb-6">
            <div class="p-6 border-b">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="text-2xl font-bold text-gray-900">{{ $request->title }}</span>
                            <span
                                class="px-3 py-1 text-xs font-medium rounded-full
                                @if ($request->status === 'approved') bg-green-100 text-green-800
                                @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($request->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($request->status === 'in_review') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                            <span
                                class="px-3 py-1 text-xs font-medium rounded-full
                                @if ($request->priority === 'urgent') bg-red-100 text-red-800
                                @elseif($request->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($request->priority === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($request->priority) }} Priority
                            </span>
                        </div>
                        <p class="text-gray-600 mt-1">{{ $request->description }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ number_format($request->amount, 2) }} {{ $request->currency }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ count($request->line_items) }} items
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Compact Approval Timeline --}}
            @if ($request->status !== 'draft')
                <div class="p-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-4">Approval Progress</h4>

                    <div class="relative">
                        <!-- Progress bar background -->
                        <div class="absolute top-4 left-0 right-0 h-2 bg-gray-200 rounded-full"></div>

                        <!-- Progress bar fill -->
                        @php
                            $progressPercent = match ($request->status) {
                                'submitted' => 20,
                                'junior_approved' => 40,
                                'senior_approved' => 60,
                                'manager_approved' => 80,
                                'payment_processing' => 100,
                                'rejected' => 0,
                                default => 0,
                            };
                            $progressColor = $request->status === 'rejected' ? 'bg-red-500' : 'bg-green-500';
                        @endphp
                        <div class="absolute top-4 left-0 h-2 {{ $progressColor }} rounded-full"
                            style="width: {{ $progressPercent }}%"></div>

                        <!-- Steps -->
                        <div class="relative flex justify-between">
                            @php
                                $compactSteps = [
                                    ['status' => 'draft', 'label' => 'Draft', 'icon' => '📝'],
                                    ['status' => 'submitted', 'label' => 'Submitted', 'icon' => '📤'],
                                    ['status' => 'junior_approved', 'label' => 'Jr Mgr', 'icon' => '👤'],
                                    ['status' => 'senior_approved', 'label' => 'Sr Mgr', 'icon' => '👨‍💼'],
                                    ['status' => 'manager_approved', 'label' => 'Final', 'icon' => '✅'],
                                    ['status' => 'payment_processing', 'label' => 'Payment', 'icon' => '💳'],
                                ];
                                // Find current step index
                                $currentIndex = 0;
                                $isRejected = $request->status === 'rejected';
                            @endphp

                            @foreach ($compactSteps as $step)
                                @php
                                    $stepIndex = array_search($step['status'], array_column($compactSteps, 'status'));
                                    $isCompleted = $stepIndex < $currentIndex;
                                    $isCurrent = $stepIndex === $currentIndex && !$isRejected;
                                    $isRejectedStep = $isRejected;
                                @endphp

                                <div class="flex flex-col items-center relative" style="z-index: 10;">
                                    <!-- Step indicator -->
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center mb-2
                        @if ($isRejectedStep) bg-red-100 text-red-600 border-2 border-red-300
                        @elseif($isCompleted) bg-green-600 text-white
                        @elseif($isCurrent) bg-green-100 text-green-600 border-2 border-green-300
                        @else bg-gray-100 text-gray-400 border-2 border-gray-200 @endif">
                                        {{ $step['icon'] }}
                                    </div>

                                    <!-- Step label -->
                                    <span
                                        class="text-xs
                        @if ($isRejectedStep) text-red-600 font-medium
                        @elseif($isCompleted || $isCurrent) text-gray-900 font-medium
                        @else text-gray-500 @endif">
                                        {{ $step['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Current status indicator -->
                    <div class="mt-6 text-center">
                        <div
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            @if ($isRejected) bg-red-100 text-red-800
            @else bg-blue-100 text-blue-800 @endif">


                            <div>
                                <span class="mr-2">{{ $isRejected ? '❌' : '📋' }}</span>
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </div>

                        </div>
                    </div>
                    @if ($isRejected)
                        <div class="mt-6 text-center">
                            <p class="mb-2">Rejection Reason</p>
                            <div
                                class="inline-flex  items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ $request->rejection_reason ?? 'No rejection reason provided' }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="space-y-8 print:space-y-6">
            {{-- HEADER SECTION --}}
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm print:shadow-none print:border print:rounded-lg">
                <div class="p-6 border-b border-gray-200 print:py-4 print:px-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 print:text-xl">Purchase Request</h1>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 print:bg-transparent print:px-0 print:py-0">
                                    #{{ $request->id }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    Created: {{ $request->created_at->format('F d, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div
                                class="inline-flex items-center px-4 py-2 rounded-lg font-semibold
                        @if ($request->status === 'approved') bg-green-100 text-green-800
                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                        @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif
                        print:bg-transparent print:border print:px-3 print:py-1">
                                {{ ucfirst($request->status) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8 print:space-y-6" id="printable-content">
                <!-- PRINT BUTTON (Top Right) -->
                <div class="flex justify-end print:hidden">
                    <button onclick="window.print()"
                        class="flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Request
                    </button>
                </div>

                <!-- SECTION 1: REQUESTER & REQUEST INFO -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 print:grid-cols-2 print:gap-4">
                    <!-- REQUESTER INFORMATION CARD -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm print:shadow-none print:border print:rounded-lg">
                        <div class="p-6 border-b border-gray-200 print:py-4 print:px-4">
                            <h2 class="text-xl font-bold text-gray-900 print:text-lg">Requester Information</h2>
                        </div>
                        <div class="p-6 print:p-4">
                            <div class="flex items-start">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 print:w-10 print:h-10">
                                    <svg class="w-6 h-6 text-gray-500 print:w-5 print:h-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="font-bold text-gray-900 print:text-sm">
                                        {{ App\Models\User::where('id', $request->user_id)->first()->name ?? 'Unknown User' }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-600 print:text-xs">
                                        {{ App\Models\User::where('id', $request->user_id)->first()->email ?? 'No email specified' }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-600 print:text-xs">
                                        {{ App\Models\User::where('id', $request->user_id)->first()->department ?? 'No department specified' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- REQUEST INFORMATION CARD -->
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm print:shadow-none print:border print:rounded-lg">
                        <div class="p-6 border-b border-gray-200 print:py-4 print:px-4">
                            <h2 class="text-xl font-bold text-gray-900 print:text-lg">Request Details</h2>
                        </div>
                        <div class="p-6 print:p-4">
                            <div class="grid grid-cols-2 gap-4 print:gap-3">
                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wide text-gray-500 print:text-xs">Request
                                        Type</label>
                                    <div class="mt-1 font-medium text-gray-900 print:text-sm">
                                        {{ ucfirst(str_replace('_', ' ', $request->type)) }}</div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wide text-gray-500 print:text-xs">Category</label>
                                    <div class="mt-1 font-medium text-gray-900 print:text-sm">
                                        {{ ucfirst($request->category ?? 'Not specified') }}</div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wide text-gray-500 print:text-xs">Request
                                        Date</label>
                                    <div class="mt-1 font-medium text-gray-900 print:text-sm">
                                        {{ $request->request_date->format('M d, Y') }}</div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wide text-gray-500 print:text-xs">Required
                                        By</label>
                                    <div class="mt-1 font-medium text-gray-900 print:text-sm">
                                        {{ $request->required_by_date ? $request->required_by_date->format('M d, Y') : 'Not specified' }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wide text-gray-500 print:text-xs">Payment
                                        Method</label>
                                    <div class="mt-1 font-medium text-gray-900 print:text-sm">
                                        {{ ucfirst(str_replace('_', ' ', $request->payment_method ?? 'Not specified')) }}
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wide text-gray-500 print:text-xs">Budget
                                        Code</label>
                                    <div class="mt-1 font-medium text-gray-900 print:text-sm">
                                        {{ $request->budget_code ?? 'Not specified' }}</div>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 print:mt-3 print:pt-3">
                                <label
                                    class="block text-xs font-semibold uppercase tracking-wide text-gray-500 print:text-xs">Project/Tender
                                    Reference</label>
                                <div class="mt-1 font-medium text-gray-900 print:text-sm">
                                    {{ $request->related_project ?? 'Not specified' }}</div>
                            </div>
                            @if ($request->notes)
                                <div class="mt-4 pt-4 border-t border-gray-100 print:mt-3 print:pt-3">
                                    <label
                                        class="block text-xs font-semibold uppercase tracking-wide text-gray-500 print:text-xs">Additional
                                        Notes</label>
                                    <div class="mt-1 text-gray-700 print:text-sm">{{ nl2br(e($request->notes)) }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: ITEMS TABLE & AMOUNT -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm print:shadow-none print:border print:rounded-lg">
                    <div class="p-6 border-b border-gray-200 print:py-4 print:px-4">
                        <h2 class="text-xl font-bold text-gray-900 print:text-lg">Request Items</h2>
                    </div>
                    <div class="overflow-x-auto print:overflow-visible">
                        <table class="w-full text-sm print:text-xs">
                            <thead class="bg-gray-50 print:bg-gray-100">
                                <tr>
                                    <th class="p-4 text-left font-semibold text-gray-700 border-b print:p-3">Item</th>
                                    <th class="p-4 text-left font-semibold text-gray-700 border-b print:p-3">
                                        Description</th>
                                    <th class="p-4 text-center font-semibold text-gray-700 border-b print:p-3">Qty</th>
                                    <th class="p-4 text-center font-semibold text-gray-700 border-b print:p-3">Unit
                                        Price</th>
                                    <th class="p-4 text-center font-semibold text-gray-700 border-b print:p-3">Tax</th>
                                    <th class="p-4 text-right font-semibold text-gray-700 border-b print:p-3">Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($request->line_items as $item)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 print:border-b">
                                        <td class="p-4 font-medium text-gray-900 print:p-3">{{ $item['name'] }}</td>
                                        <td class="p-4 text-gray-600 print:p-3">{{ $item['description'] ?? '—' }}</td>
                                        <td class="p-4 text-center print:p-3">{{ $item['quantity'] }}</td>
                                        <td class="p-4 text-center print:p-3">
                                            {{ number_format($item['unit_price'], 2) }} {{ $request->currency }}
                                        </td>
                                        <td class="p-4 text-center print:p-3">{{ $item['tax_rate'] }}%</td>
                                        <td class="p-4 text-right font-medium print:p-3">
                                            {{ number_format($item['total'], 2) }} {{ $request->currency }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 print:bg-gray-100">
                                <tr>
                                    <td colspan="5" class="p-4 text-right font-semibold text-gray-700 print:p-3">
                                        Subtotal</td>
                                    <td class="p-4 text-right font-semibold text-gray-900 print:p-3">
                                        {{ number_format($request->line_items[0]['subtotal'], 2) }}
                                        {{ $request->currency }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="p-4 text-right font-semibold text-gray-700 print:p-3">
                                        Total Tax</td>
                                    <td class="p-4 text-right font-semibold text-gray-900 print:p-3">
                                        {{ number_format(array_sum(array_column($request->line_items, 0, 'tax_amount')), 2) }}
                                        {{ $request->currency }}
                                    </td>
                                </tr>
                                <tr class="border-t border-gray-200">
                                    <td colspan="5"
                                        class="p-4 text-right font-bold text-gray-900 text-lg print:p-3 print:text-base">
                                        Grand Total</td>
                                    <td
                                        class="p-4 text-right font-bold text-blue-700 text-lg print:p-3 print:text-base">
                                        {{ number_format($request->amount, 2) }} {{ $request->currency }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- SECTION 3: SUPPORTING DOCUMENTS -->
                @if (!empty($request->supporting_documents))
                    <div
                        class="bg-white border border-gray-200 rounded-xl shadow-sm print:shadow-none print:border print:rounded-lg">
                        <div class="p-6 border-b border-gray-200 print:py-4 print:px-4">
                            <h2 class="text-xl font-bold text-gray-900 print:text-lg">
                                Supporting Documents
                            </h2>
                        </div>

                        <div class="p-6 print:p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 print:gap-2">

                                @foreach ($request->supporting_documents as $attachment)
                                    @php
                                        $extension = strtolower(pathinfo($attachment['path'], PATHINFO_EXTENSION));

                                        $icon = match ($extension) {
                                            'pdf' => '📄',
                                            'doc', 'docx' => '📝',
                                            'xls', 'xlsx' => '📊',
                                            'jpg', 'jpeg', 'png', 'gif' => '🖼️',
                                            default => '📎',
                                        };

                                        $isPreviewable = in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'gif']);
                                        $fileUrl = Storage::url($attachment['path']);
                                    @endphp

                                    <div class="border border-gray-200 rounded-lg p-3 flex flex-col gap-3 print:p-2">

                                        <!-- Header -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center min-w-0">
                                                <span class="text-lg mr-3 print:text-base">{{ $icon }}</span>

                                                <div class="min-w-0">
                                                    <div class="font-medium text-gray-900 truncate print:text-sm">
                                                        {{ $attachment['name'] }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center gap-3 text-sm print:hidden">
                                                @if ($isPreviewable)
                                                    <a href="{{ $fileUrl }}" target="_blank"
                                                        class="text-blue-600 hover:underline">
                                                        Download
                                                    </a>
                                                @endif


                                            </div>
                                        </div>

                                        <!-- Inline Preview -->
                                        @if ($isPreviewable)
                                            <div class="border rounded-md overflow-hidden print:hidden">
                                                @if ($extension === 'pdf')
                                                    <iframe src="{{ $fileUrl }}" class="w-full h-56"
                                                        loading="lazy">
                                                    </iframe>
                                                @else
                                                    <img src="{{ $fileUrl }}" alt="{{ $attachment['name'] }}"
                                                        class="w-full h-56 object-contain bg-gray-50">
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endif


                <!-- SECTION 4: AUDIT TRAIL (Bottom - Not Printed) -->
                @if ($request->status !== 'draft')
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm print:hidden" id="audit-trail">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Approval Timeline</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Main Activity Timeline -->
                                <div class="relative">
                                    <!-- Timeline line -->
                                    <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                                    <!-- Timeline items -->
                                    <div class="space-y-8">
                                        <!-- Request Created (Always shows) -->
                                        <div class="relative flex items-start">
                                            <div
                                                class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0 z-10">
                                                <svg class="w-6 h-6 text-green-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-gray-900">Request Created</div>
                                                <div class="mt-1 text-gray-600">Draft created and saved</div>
                                                <div class="mt-2 text-sm text-gray-500">
                                                    {{ $request->created_at->format('F j, Y \a\t g:i A') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Request Submitted (Shows when not draft) -->
                                        <div class="relative flex items-start">
                                            <div
                                                class="w-12 h-12
                            @if (in_array($request->status, [
                                    'submitted',
                                    'junior_approved',
                                    'senior_approved',
                                    'manager_approved',
                                    'payment_processing',
                                ])) bg-blue-100 text-blue-600
                            @else
                                bg-gray-100 text-gray-400 @endif
                            rounded-full flex items-center justify-center mr-4 flex-shrink-0 z-10">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-gray-900">Request Submitted</div>
                                                <div class="mt-1 text-gray-600">
                                                    @if (in_array($request->status, [
                                                            'submitted',
                                                            'junior_approved',
                                                            'senior_approved',
                                                            'manager_approved',
                                                            'payment_processing',
                                                        ]))
                                                        Submitted for approval
                                                    @else
                                                        Pending submission
                                                    @endif
                                                </div>
                                                @if ($request->submitted_at)
                                                    <div class="mt-2 text-sm text-gray-500">
                                                        {{ $request->submitted_at->format('F j, Y \a\t g:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Junior Approval -->
                                        <div class="relative flex items-start">
                                            <div
                                                class="w-12 h-12
                            @if (in_array($request->status, ['junior_approved', 'senior_approved', 'manager_approved', 'payment_processing'])) bg-green-100 text-green-600
                            @elseif($request->status === 'submitted')
                                bg-yellow-100 text-yellow-600
                            @else
                                bg-gray-100 text-gray-400 @endif
                            rounded-full flex items-center justify-center mr-4 flex-shrink-0 z-10">
                                                @if (in_array($request->status, ['junior_approved', 'senior_approved', 'manager_approved', 'payment_processing']))
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif($request->status === 'submitted')
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-gray-900">Junior Manager Approval</div>
                                                <div class="mt-1 text-gray-600">
                                                    @if (in_array($request->status, ['junior_approved', 'senior_approved', 'manager_approved', 'payment_processing']))
                                                        Approved by Junior Manager
                                                    @elseif($request->status === 'submitted')
                                                        Awaiting Junior Manager approval
                                                    @else
                                                        Pending
                                                    @endif
                                                </div>
                                                @if ($request->junior_approved_at)
                                                    <div class="mt-2 text-sm text-gray-500">
                                                        {{ $request->junior_approved_at->format('F j, Y \a\t g:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Senior Approval -->
                                        <div class="relative flex items-start">
                                            <div
                                                class="w-12 h-12
                            @if (in_array($request->status, ['senior_approved', 'manager_approved', 'payment_processing'])) bg-green-100 text-green-600
                            @elseif($request->status === 'junior_approved')
                                bg-yellow-100 text-yellow-600
                            @else
                                bg-gray-100 text-gray-400 @endif
                            rounded-full flex items-center justify-center mr-4 flex-shrink-0 z-10">
                                                @if (in_array($request->status, ['senior_approved', 'manager_approved', 'payment_processing']))
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif($request->status === 'junior_approved')
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-gray-900">Senior Manager Approval</div>
                                                <div class="mt-1 text-gray-600">
                                                    @if (in_array($request->status, ['senior_approved', 'manager_approved', 'payment_processing']))
                                                        Approved by Senior Manager
                                                    @elseif($request->status === 'junior_approved')
                                                        Awaiting Senior Manager approval
                                                    @else
                                                        Pending
                                                    @endif
                                                </div>
                                                @if ($request->senior_approved_at)
                                                    <div class="mt-2 text-sm text-gray-500">
                                                        {{ $request->senior_approved_at->format('F j, Y \a\t g:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Manager Approval -->
                                        <div class="relative flex items-start">
                                            <div
                                                class="w-12 h-12
                            @if (in_array($request->status, ['manager_approved', 'payment_processing'])) bg-green-100 text-green-600
                            @elseif($request->status === 'senior_approved')
                                bg-yellow-100 text-yellow-600
                            @else
                                bg-gray-100 text-gray-400 @endif
                            rounded-full flex items-center justify-center mr-4 flex-shrink-0 z-10">
                                                @if (in_array($request->status, ['manager_approved', 'payment_processing']))
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif($request->status === 'senior_approved')
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-gray-900">Final Manager Approval</div>
                                                <div class="mt-1 text-gray-600">
                                                    @if (in_array($request->status, ['manager_approved', 'payment_processing']))
                                                        Approved by Final Manager
                                                    @elseif($request->status === 'senior_approved')
                                                        Awaiting Final Manager approval
                                                    @else
                                                        Pending
                                                    @endif
                                                </div>
                                                @if ($request->manager_approved_at)
                                                    <div class="mt-2 text-sm text-gray-500">
                                                        {{ $request->manager_approved_at->format('F j, Y \a\t g:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Payment Processing -->
                                        <div class="relative flex items-start">
                                            <div
                                                class="w-12 h-12
                            @if ($request->status === 'payment_processing') bg-green-100 text-green-600
                            @elseif($request->status === 'manager_approved')
                                bg-yellow-100 text-yellow-600
                            @else
                                bg-gray-100 text-gray-400 @endif
                            rounded-full flex items-center justify-center mr-4 flex-shrink-0 z-10">
                                                @if ($request->status === 'payment_processing')
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif($request->status === 'manager_approved')
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-bold text-gray-900">Payment Processing</div>
                                                <div class="mt-1 text-gray-600">
                                                    @if ($request->status === 'payment_processing')
                                                        Payment is being processed
                                                    @elseif($request->status === 'manager_approved')
                                                        Ready for payment processing
                                                    @else
                                                        Pending final approval
                                                    @endif
                                                </div>
                                                @if ($request->payment_processed_at)
                                                    <div class="mt-2 text-sm text-gray-500">
                                                        {{ $request->payment_processed_at->format('F j, Y \a\t g:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Rejected Status (Shows only if rejected) -->
                                        @if ($request->status === 'rejected')
                                            <div class="relative flex items-start">
                                                <div
                                                    class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mr-4 flex-shrink-0 z-10">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-900">Request Rejected</div>
                                                    <div class="mt-1 text-gray-600">
                                                        The request was rejected at this stage
                                                    </div>
                                                    @if ($request->rejected_at)
                                                        <div class="mt-2 text-sm text-gray-500">
                                                            {{ $request->rejected_at->format('F j, Y \a\t g:i A') }}
                                                        </div>
                                                    @endif
                                                    @if ($request->rejection_reason)
                                                        <div
                                                            class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                            <div class="text-sm font-medium text-red-800">Reason:</div>
                                                            <div class="text-sm text-red-700 mt-1">
                                                                {{ $request->rejection_reason }}</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Completed Status (Add if you have 'completed' status) -->
                                        @if ($request->status === 'completed')
                                            <div class="relative flex items-start">
                                                <div
                                                    class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mr-4 flex-shrink-0 z-10">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-900">Request Completed</div>
                                                    <div class="mt-1 text-gray-600">
                                                        Payment processed and request completed
                                                    </div>
                                                    @if ($request->completed_at)
                                                        <div class="mt-2 text-sm text-gray-500">
                                                            {{ $request->completed_at->format('F j, Y \a\t g:i A') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- PRINT FOOTER (Only shows when printing) -->
                <div class="hidden print:block mt-8 pt-6 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        <div class="flex justify-between">
                            <div>
                                Request ID: {{ $request->id }}<br>
                                Printed: {{ now()->format('F d, Y \a\t h:i A') }}
                            </div>
                            <div class="text-right">
                                {{ config('app.name', 'Purchase Request System') }}<br>
                                Page 1 of 1
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRINT STYLES -->
            <style>
                @media print {

                    /* Hide non-printable elements */
                    .print\:hidden,
                    #audit-trail,
                    button {
                        display: none !important;
                    }

                    /* Ensure all print content is visible */
                    .print\:block {
                        display: block !important;
                    }

                    /* Page setup */
                    @page {
                        margin: 0.5in;
                        size: letter;
                    }

                    /* Improve readability */
                    body {
                        font-size: 11pt;
                        line-height: 1.3;
                    }

                    /* Ensure proper spacing */
                    .space-y-8>*+* {
                        margin-top: 1.5rem !important;
                    }

                    /* Table improvements */
                    table {
                        page-break-inside: avoid;
                        border-collapse: collapse;
                    }

                    th,
                    td {
                        padding: 6px 8px !important;
                    }

                    /* Card styling for print */
                    .bg-white {
                        background: white !important;
                        border: 1px solid #d1d5db !important;
                    }

                    /* Remove shadows and rounded corners */
                    .rounded-xl,
                    .rounded-lg {
                        border-radius: 0.125rem !important;
                    }

                    .shadow-sm {
                        box-shadow: none !important;
                    }
                }
            </style>
        </div>
</x-layouts.dashboard>
