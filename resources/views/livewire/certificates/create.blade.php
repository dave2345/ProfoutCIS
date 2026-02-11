<div>
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create New Certificate</h1>
            <p class="text-gray-600 mt-2">Fill in the certificate details or extract from uploaded files</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: File Upload and Extraction -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Upload Certificate Files</h2>

                <!-- File Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Certificate (PDF/Images)
                    </label>
                    <div x-data="{ files: [] }" class="space-y-4">
                        <input type="file" wire:model="files" multiple accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full form-input">

                        @if ($files)
                            <div class="space-y-2">
                                @foreach ($files as $index => $file)
                                    <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                        <div class="flex items-center">
                                            <i
                                                class="fas fa-file-{{ str_contains($file->getMimeType(), 'image') ? 'image' : 'pdf' }} text-gray-400 mr-2"></i>
                                            <span class="text-sm">{{ $file->getClientOriginalName() }}</span>
                                        </div>
                                        <button type="button" wire:click="removeFile({{ $index }})"
                                            class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Extract Data Button -->
                <button wire:click="extractData"  class="btn btn-primary w-full">
                    <span wire:loading.remove wire:target="extractData">
                        <i class="fas fa-extract mr-2"></i> Extract Data from Files
                    </span>
                    <span wire:loading wire:target="extractData">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Extracting...
                    </span>
                </button>

                <!-- Extracted Data Preview -->
                <!-- Extraction Results -->
                @if (!empty($extractedData))
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Extraction Results</h3>

                        <div class="bg-white rounded-lg border overflow-hidden">
                            <div class="grid grid-cols-6 bg-gray-50 border-b">
                                <div class="p-3 font-medium text-sm">File</div>
                                <div class="p-3 font-medium text-sm">Method</div>
                                <div class="p-3 font-medium text-sm">Title</div>
                                <div class="p-3 font-medium text-sm">Cert #</div>
                                <div class="p-3 font-medium text-sm">Authority</div>
                                <div class="p-3 font-medium text-sm">Status</div>
                            </div>

                            @foreach ($extractedData as $index => $data)
                                <div class="grid grid-cols-6 border-b hover:bg-gray-50">
                                    <div class="p-3 text-sm truncate" title="{{ $data['filename'] }}">
                                        {{ Str::limit($data['filename'], 20) }}
                                    </div>
                                    <div class="p-3 text-sm">
                                        <span
                                            class="px-2 py-1 rounded text-xs
                            @if ($data['extraction_method'] == 'pdf_parser') bg-blue-100 text-blue-800
                            @elseif($data['extraction_method'] == 'ocr') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $data['extraction_method'] }}
                                        </span>
                                    </div>
                                    <div class="p-3 text-sm">{{ $data['title'] ?? 'N/A' }}</div>
                                    <div class="p-3 text-sm font-mono">{{ $data['certificate_number'] ?? 'N/A' }}</div>
                                    <div class="p-3 text-sm">{{ $data['issuing_authority'] ?? 'N/A' }}</div>
                                    <div class="p-3 text-sm">
                                        @if ($data['success'])
                                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i> Success
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i> Failed
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Extraction Summary -->
                        <div class="mt-4 flex items-center justify-between text-sm">
                            <div>
                                <span class="text-gray-600">Extraction Summary:</span>
                                <span class="ml-2 font-medium">
                                    {{ $extractionSummary['successful'] }} successful /
                                    {{ $extractionSummary['total'] }} total
                                </span>
                            </div>
                            <button type="button" wire:click="clearExtractedData"
                                class="text-red-600 hover:text-red-800 text-sm">
                                <i class="fas fa-trash mr-1"></i> Clear Results
                            </button>
                        </div>

                        <!-- Auto-filled Notice -->
                        @if ($extractionSummary['successful'] > 0)
                            <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-green-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            <strong>Note:</strong> Form fields have been auto-filled with extracted data
                                            from successful extractions.
                                            You can review and edit the information before saving.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Extraction Errors -->
                @if (!empty($extractionErrors))
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-red-700 mb-2">Extraction Errors</h4>
                        <div class="space-y-2">
                            @foreach ($extractionErrors as $error)
                                <div class="bg-red-50 border-l-4 border-red-400 p-3">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700">
                                                <strong>{{ $error['filename'] }}:</strong> {{ $error['error'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Certificate Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Certificate Details</h2>

                <form wire:submit.prevent="save">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Certificate Number *</label>
                            <input type="text" wire:model="certificate_number" class="mt-1 form-input w-full">
                            @error('certificate_number')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" wire:model="title" class="mt-1 form-input w-full">
                            @error('title')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type *</label>
                            <select wire:model="type" class="mt-1 form-select w-full">
                                @foreach (['compliance', 'accreditation', 'license', 'award', 'training', 'membership', 'other'] as $typeOption)
                                    <option value="{{ $typeOption }}">{{ ucfirst($typeOption) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status *</label>
                            <select wire:model="status" class="mt-1 form-select w-full">
                                @foreach (['draft', 'active', 'expired', 'revoked', 'renewed'] as $statusOption)
                                    <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Issuing Authority -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Issuing Authority *</label>
                        <input type="text" wire:model="issuing_authority" class="mt-1 form-input w-full">
                        @error('issuing_authority')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Issue Date *</label>
                            <input type="date" wire:model="issue_date" class="mt-1 form-input w-full">
                            @error('issue_date')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input type="date" wire:model="expiry_date" class="mt-1 form-input w-full">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Renewal Date</label>
                            <input type="date" wire:model="renewal_date" class="mt-1 form-input w-full">
                        </div>
                    </div>

                    <!-- Validity Period -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Validity Period</label>
                        <input type="text" wire:model="validity_period" placeholder="e.g., 1 year, 6 months"
                            class="mt-1 form-input w-full">
                    </div>

                    <!-- Related Entities -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Related Project</label>
                            <select wire:model="related_project_id" class="mt-1 form-select w-full">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Related Tender</label>
                            <select wire:model="related_tender_id" class="mt-1 form-select w-full">
                                <option value="">Select Tender</option>
                                @foreach ($tenders as $tender)
                                    <option value="{{ $tender->id }}">{{ $tender->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Renewal Settings -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center mb-4">
                            <input type="checkbox" wire:model="is_renewable" id="is_renewable" class="mr-2">
                            <label for="is_renewable" class="font-medium text-gray-700">
                                This certificate is renewable
                            </label>
                        </div>

                        @if ($is_renewable)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Renewal Reminder (days before expiry)
                                </label>
                                <input type="number" wire:model="renewal_reminder_days" min="0"
                                    class="mt-1 form-input w-full">
                            </div>
                        @endif
                    </div>

                    <!-- Description & Notes -->
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" rows="3" class="mt-1 form-textarea w-full"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea wire:model="notes" rows="2" class="mt-1 form-textarea w-full"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('certificates.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">
                                Create Certificate
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Creating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('message') }}
        </div>
    @endif
    @push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Listen for extraction complete event
        Livewire.on('extraction-complete', (data) => {
            // Show toast notification
            showToast(`Extraction completed: ${data.successful} out of ${data.total} files processed successfully.`, 'success');

            // Scroll to extraction results
            const resultsSection = document.querySelector('[data-extraction-results]');
            if (resultsSection) {
                resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        // Show loading state during extraction
        Livewire.on('extraction-started', () => {
            // You can show a loading overlay here if needed
        });

        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            // Add to DOM
            document.body.appendChild(toast);

            // Remove after 5 seconds
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
    });
</script>
@endpush
</div>
