<div>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Certificate</h1>
            <p class="text-gray-600 mt-2">Update certificate details for {{ $certificate->certificate_number }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Certificate Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Certificate Details</h2>

                <form wire:submit.prevent="update">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Certificate Number *</label>
                            <input type="text" wire:model="certificate_number"
                                   class="mt-1 form-input w-full">
                            @error('certificate_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" wire:model="title"
                                   class="mt-1 form-input w-full">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type *</label>
                            <select wire:model="type" class="mt-1 form-select w-full">
                                @foreach(['compliance', 'accreditation', 'license', 'award', 'training', 'membership', 'other'] as $typeOption)
                                    <option value="{{ $typeOption }}">{{ ucfirst($typeOption) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status *</label>
                            <select wire:model="status" class="mt-1 form-select w-full">
                                @foreach(['draft', 'active', 'expired', 'revoked', 'renewed'] as $statusOption)
                                    <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Issuing Authority -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Issuing Authority *</label>
                        <input type="text" wire:model="issuing_authority"
                               class="mt-1 form-input w-full">
                        @error('issuing_authority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Issue Date *</label>
                            <input type="date" wire:model="issue_date"
                                   class="mt-1 form-input w-full">
                            @error('issue_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input type="date" wire:model="expiry_date"
                                   class="mt-1 form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Renewal Date</label>
                            <input type="date" wire:model="renewal_date"
                                   class="mt-1 form-input w-full">
                        </div>
                    </div>

                    <!-- Validity Period -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Validity Period</label>
                        <input type="text" wire:model="validity_period"
                               placeholder="e.g., 1 year, 6 months"
                               class="mt-1 form-input w-full">
                    </div>

                    <!-- Related Entities -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Related Project</label>
                            <select wire:model="related_project_id" class="mt-1 form-select w-full">
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Related Tender</label>
                            <select wire:model="related_tender_id" class="mt-1 form-select w-full">
                                <option value="">Select Tender</option>
                                @foreach($tenders as $tender)
                                    <option value="{{ $tender->id }}">{{ $tender->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Renewal Settings -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center mb-4">
                            <input type="checkbox" wire:model="is_renewable"
                                   id="is_renewable" class="mr-2">
                            <label for="is_renewable" class="font-medium text-gray-700">
                                This certificate is renewable
                            </label>
                        </div>

                        @if($is_renewable)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Renewal Reminder (days before expiry)
                                </label>
                                <input type="number" wire:model="renewal_reminder_days"
                                       min="0" class="mt-1 form-input w-full">
                            </div>
                        @endif
                    </div>

                    <!-- Description & Notes -->
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description"
                                      rows="3"
                                      class="mt-1 form-textarea w-full"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea wire:model="notes"
                                      rows="2"
                                      class="mt-1 form-textarea w-full"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('certificates.show', $certificate) }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="btn btn-primary">
                            <span wire:loading.remove wire:target="update">
                                Update Certificate
                            </span>
                            <span wire:loading wire:target="update">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Updating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Attachments Management -->
            <div class="space-y-6">
                <!-- Existing Attachments -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Current Attachments</h2>

                    @if(count($existingAttachments) > 0)
                        <div class="space-y-3">
                            @foreach($existingAttachments as $index => $attachment)
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="p-2 bg-gray-100 rounded">
                                            @if(str_contains($attachment['type'], 'image'))
                                                <i class="fas fa-image text-gray-400"></i>
                                            @else
                                                <i class="fas fa-file-pdf text-red-400"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $attachment['name'] }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ strtoupper(pathinfo($attachment['name'], PATHINFO_EXTENSION)) }} •
                                                {{ round($attachment['size'] / 1024, 1) }} KB
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            wire:click="removeExistingAttachment({{ $index }})"
                                            onclick="return confirm('Are you sure you want to remove this file?')"
                                            class="text-red-500 hover:text-red-700 p-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open text-gray-300 text-3xl mb-2"></i>
                            <p class="text-gray-500 text-sm">No attachments uploaded</p>
                        </div>
                    @endif
                </div>

                <!-- Add New Attachments -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Add New Attachments</h2>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload Additional Files (PDF/Images)
                        </label>
                        <input type="file"
                               wire:model="newFiles"
                               multiple
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full form-input">
                    </div>

                    <!-- New Files Preview -->
                    @if(count($newFiles) > 0)
                        <div class="space-y-2 mb-4">
                            <p class="text-sm font-medium text-gray-700">New files to be added:</p>
                            @foreach($newFiles as $index => $file)
                                <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-{{ str_contains($file->getMimeType(), 'image') ? 'image' : 'pdf' }} text-gray-400 mr-2"></i>
                                        <span class="text-sm">{{ $file->getClientOriginalName() }}</span>
                                    </div>
                                    <button type="button"
                                            wire:click="removeNewFile({{ $index }})"
                                            class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- File Limits Info -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Accepted formats:</strong> PDF, JPG, JPEG, PNG<br>
                                    <strong>Max file size:</strong> 10MB per file
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Information Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Certificate Information</h2>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Certificate ID:</span>
                            <span class="text-sm font-medium">{{ $certificate->id }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Created By:</span>
                            <span class="text-sm font-medium">{{ $certificate->user->name ?? 'Unknown' }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Created Date:</span>
                            <span class="text-sm font-medium">{{ $certificate->created_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Last Updated:</span>
                            <span class="text-sm font-medium">{{ $certificate->updated_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Attachments:</span>
                            <span class="text-sm font-medium">
                                {{ count($existingAttachments) + count($newFiles) }} files
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <a href="{{ route('certificates.show', $certificate) }}"
                           class="block w-full text-center btn btn-outline">
                            <i class="fas fa-eye mr-2"></i> View Certificate
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('message'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('message') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-hide flash messages
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('[x-data*="show"]');
            flashMessages.forEach(function(message) {
                if (message.__x) {
                    message.__x.$data.show = false;
                }
            });
        }, 5000);
    });

    // Confirm before removing existing attachments
    document.addEventListener('livewire:load', function() {
        Livewire.on('confirmRemoveAttachment', function(data) {
            if (confirm('Are you sure you want to remove this attachment?')) {
                Livewire.emit('removeAttachmentConfirmed', data.index);
            }
        });
    });
</script>
@endpush
