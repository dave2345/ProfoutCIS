<div>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Certificates</h1>
            <a href="{{ route('certificates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Add Certificate
            </a>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" wire:model.debounce.300ms="search"
                           placeholder="Search certificates..."
                           class="w-full form-input">
                </div>
                <div>
                    <select wire:model="type" class="w-full form-select">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model="status" class="w-full form-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select wire:model="perPage" class="w-full form-select">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Certificates Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('certificate_number')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Certificate #
                                @if($sortField === 'certificate_number')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('title')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Title
                                @if($sortField === 'title')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th wire:click="sortBy('issue_date')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Issue Date
                                @if($sortField === 'issue_date')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Attachments
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($certificates as $certificate)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $certificate->certificate_number }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $certificate->title }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $certificate->issuing_authority }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($certificate->type === 'compliance') bg-blue-100 text-blue-800
                                        @elseif($certificate->type === 'accreditation') bg-green-100 text-green-800
                                        @elseif($certificate->type === 'license') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($certificate->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($certificate->status === 'active') bg-green-100 text-green-800
                                        @elseif($certificate->status === 'expired') bg-red-100 text-red-800
                                        @elseif($certificate->status === 'draft') bg-yellow-100 text-yellow-800
                                        @elseif($certificate->status === 'revoked') bg-gray-100 text-gray-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($certificate->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $certificate->issue_date->format('M d, Y') }}
                                    @if($certificate->expiry_date)
                                        <div class="text-xs text-gray-400">
                                            Expires: {{ $certificate->expiry_date->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($certificate->attachments && count($certificate->attachments) > 0)
                                        <div class="flex space-x-2">
                                            @foreach($certificate->attachments as $index => $attachment)
                                                <button wire:click="downloadAttachment({{ $certificate->id }}, {{ $index }})"
                                                        class="text-blue-600 hover:text-blue-800 text-sm"
                                                        title="Download {{ $attachment['name'] }}">
                                                    <i class="fas fa-file-{{ str_contains($attachment['type'], 'image') ? 'image' : 'pdf' }} mr-1"></i>
                                                    {{ Str::limit($attachment['name'], 10) }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No attachments</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('certificates.show', $certificate) }}"
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('certificates.edit', $certificate) }}"
                                       class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="deleteCertificate({{ $certificate->id }})"
                                            onclick="return confirm('Are you sure?')"
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No certificates found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $certificates->links() }}
            </div>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            {{ session('message') }}
        </div>
    @endif
</div>
