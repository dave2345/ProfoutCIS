<div>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $cert->title }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="text-lg text-gray-600">{{ $cert->certificate_number }}</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($cert->status === 'active') bg-green-100 text-green-800
                        @elseif($cert->status === 'expired') bg-red-100 text-red-800
                        @elseif($cert->status === 'draft') bg-yellow-100 text-yellow-800
                        @elseif($cert->status === 'revoked') bg-gray-100 text-gray-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ ucfirst($cert->status) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        {{ ucfirst($cert->type) }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('certificates.edit', $cert) }}"
                   class="btn btn-secondary">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('certificates.index') }}"
                   class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Certificate Details -->
            <div class="lg:col-span-2">
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button @click="activeTab = 'details'"
                                :class="activeTab === 'details' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Details
                        </button>
                        <button @click="activeTab = 'attachments'"
                                :class="activeTab === 'attachments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Attachments ({{ count($cert->attachments ?? []) }})
                        </button>
                        <button @click="activeTab = 'timeline'"
                                :class="activeTab === 'timeline' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Timeline
                        </button>
                    </nav>
                </div>

                <!-- Details Tab -->
                <div x-show="activeTab === 'details'" class="space-y-6">
                    <!-- Issuing Authority & Dates -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Issuing Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Issuing Authority</h4>
                                <p class="mt-1 text-gray-900">{{ $cert->issuing_authority }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Validity Period</h4>
                                <p class="mt-1 text-gray-900">{{ $cert->validity_period ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Issue Date</h4>
                                <p class="mt-1 text-gray-900">{{ $cert->issue_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Expiry Date</h4>
                                <p class="mt-1 text-gray-900">
                                    {{ $cert->expiry_date ? $cert->expiry_date->format('F d, Y') : 'No expiry' }}
                                </p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Renewal Date</h4>
                                <p class="mt-1 text-gray-900">
                                    {{ $cert->renewal_date ? $cert->renewal_date->format('F d, Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Description & Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4">Description</h3>
                            <p class="text-gray-700">{{ $cert->description ?? 'No description provided' }}</p>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4">Notes</h3>
                            <p class="text-gray-700">{{ $cert->notes ?? 'No notes' }}</p>
                        </div>
                    </div>

                    <!-- Related Entities -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Related Entities</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Related Project</h4>
                                @if($cert->project)
                                    <a href="#" class="mt-1 text-blue-600 hover:text-blue-800">
                                        {{ $cert->project->name }}
                                    </a>
                                @else
                                    <p class="mt-1 text-gray-500">No project assigned</p>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Related Tender</h4>
                                @if($cert->tender)
                                    <a href="#" class="mt-1 text-blue-600 hover:text-blue-800">
                                        {{ $cert->tender->title }}
                                    </a>
                                @else
                                    <p class="mt-1 text-gray-500">No tender assigned</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Renewal Information -->
                    @if($cert->is_renewable)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-4">Renewal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Renewable</h4>
                                    <p class="mt-1 text-gray-900">Yes</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Reminder Days</h4>
                                    <p class="mt-1 text-gray-900">{{ $cert->renewal_reminder_days }} days before expiry</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Attachments Tab -->
                <div x-show="activeTab === 'attachments'" class="space-y-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Certificate Files</h3>

                        @if($cert->attachments && count($cert->attachments) > 0)
                            <div class="space-y-4">
                                @foreach($cert->attachments as $index => $attachment)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-3 bg-gray-100 rounded-lg">
                                                @if(str_contains($attachment['type'], 'image'))
                                                    <i class="fas fa-image text-gray-400 text-xl"></i>
                                                @else
                                                    <i class="fas fa-file-pdf text-red-400 text-xl"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $attachment['name'] }}</h4>
                                                <p class="text-sm text-gray-500">
                                                    {{ strtoupper(pathinfo($attachment['name'], PATHINFO_EXTENSION)) }} •
                                                    {{ round($attachment['size'] / 1024, 1) }} KB
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button wire:click="previewAttachment({{ $index }})"
                                                    class="text-blue-600 hover:text-blue-800 p-2"
                                                    title="Preview">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button wire:click="downloadAttachment({{ $index }})"
                                                    class="text-green-600 hover:text-green-800 p-2"
                                                    title="Download">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-file-upload text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">No attachments uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline Tab -->
                <div x-show="activeTab === 'timeline'" class="space-y-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Certificate Timeline</h3>
                        <div class="space-y-8">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-plus text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium text-gray-900">Certificate Created</h4>
                                    <p class="text-sm text-gray-500">{{ $cert->created_at->format('F d, Y \a\t h:i A') }}</p>
                                </div>
                            </div>

                            @if($cert->issue_date)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-check text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-medium text-gray-900">Issued</h4>
                                        <p class="text-sm text-gray-500">{{ $cert->issue_date->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($cert->expiry_date)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full
                                            @if($cert->expiry_date->isFuture()) bg-yellow-100 @else bg-red-100 @endif
                                            flex items-center justify-center">
                                            <i class="fas fa-calendar-times
                                                @if($cert->expiry_date->isFuture()) text-yellow-600 @else text-red-600 @endif"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-medium text-gray-900">Expiry Date</h4>
                                        <p class="text-sm text-gray-500">{{ $cert->expiry_date->format('F d, Y') }}</p>
                                        <p class="text-sm
                                            @if($cert->expiry_date->isFuture()) text-yellow-600 @else text-red-600 @endif">
                                            {{ $cert->expiry_date->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Metadata & Actions -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Info</h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Created By</h4>
                            <p class="mt-1 text-gray-900">{{ $cert->user->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Created On</h4>
                            <p class="mt-1 text-gray-900">{{ $cert->created_at->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Last Updated</h4>
                            <p class="mt-1 text-gray-900">{{ $cert->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('certificates.edit', $cert) }}"
                           class="block w-full text-center btn btn-secondary">
                            <i class="fas fa-edit mr-2"></i> Edit Certificate
                        </a>
                        @if($cert->attachments && count($cert->attachments) > 0)
                            <button wire:click="downloadAttachment(0)"
                                    class="block w-full text-center btn btn-outline">
                                <i class="fas fa-download mr-2"></i> Download Files
                            </button>
                        @endif
                        <a href="#"
                           class="block w-full text-center btn btn-outline text-red-600 border-red-200 hover:bg-red-50">
                            <i class="fas fa-trash mr-2"></i> Delete Certificate
                        </a>
                    </div>
                </div>

                <!-- Status Badges -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Certificate Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Current Status</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($cert->status === 'active') bg-green-100 text-green-800
                                @elseif($cert->status === 'expired') bg-red-100 text-red-800
                                @elseif($cert->status === 'draft') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($cert->status) }}
                            </span>
                        </div>

                        @if($cert->expiry_date)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700">Days to Expiry</span>
                                <span class="text-sm font-semibold
                                    @if($cert->expiry_date->diffInDays(now()) <= 30) text-red-600
                                    @elseif($cert->expiry_date->diffInDays(now()) <= 90) text-yellow-600
                                    @else text-green-600 @endif">
                                    {{ $cert->expiry_date->diffInDays(now()) }} days
                                </span>
                            </div>
                        @endif

                        @if($cert->is_renewable)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-700">Renewable</span>
                                <span class="text-green-600">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-data="{ showPreview: false }" x-show="showPreview"
         x-on:show-preview-modal.window="showPreview = true"
         x-on:keydown.escape.window="showPreview = false"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <!-- Modal -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Preview Certificate</h3>
                        <button @click="showPreview = false"
                                class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    @if($previewType && $previewUrl)
                        @if(str_contains($previewType, 'image'))
                            <img src="{{ $previewUrl }}" alt="Certificate Preview"
                                 class="max-w-full h-auto mx-auto">
                        @elseif(str_contains($previewType, 'pdf'))
                            <div class="h-96">
                                <iframe src="{{ $previewUrl }}"
                                        class="w-full h-full border-0"></iframe>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <a href="{{ $previewUrl }}"
                       target="_blank"
                       class="btn btn-primary sm:ml-3">
                        <i class="fas fa-external-link-alt mr-2"></i> Open in New Tab
                    </a>
                    <button @click="showPreview = false"
                            class="btn btn-outline mt-3 sm:mt-0">
                        Close Preview
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('certificateShow', () => ({
            activeTab: 'details',

            init() {
                // Initialize any custom functionality
            }
        }));
    });
</script>
@endpush
