<x-layouts.dashboard>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Create New Request</h2>
                <p class="text-sm text-gray-600">One request can contain multiple items</p>
            </div>
            <a href="{{ route('requests.index') }}"
               class="text-sm font-medium text-blue-600 hover:underline">
                ← Back to Requests
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        <form method="POST"
              action="{{ route('requests.store') }}"
              enctype="multipart/form-data"
              class="bg-white border rounded-xl shadow-sm p-6 space-y-8">
            @csrf

            {{-- ========================= --}}
            {{-- REQUEST HEADER --}}
            {{-- ========================= --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request Title *</label>
                        <input type="text" name="title" required
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter request title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                        <textarea name="description" rows="2" required
                                  class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Brief description of the request"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ========================= --}}
                {{-- REQUEST METADATA --}}
                {{-- ========================= --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request Type *</label>
                        <select name="type" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">

                            <option value="payment_request" {{ old('type') == 'payment_request' ? 'selected' : '' }}>Payment Request</option>
                            <option value="purchase_request" {{ old('type') == 'purchase_request' ? 'selected' : '' }}>Purchase Request</option>
                            <option value="travel_request" {{ old('type') == 'travel_request' ? 'selected' : '' }}>Travel Request</option>
                            <option value="leave_request" {{ old('type') == 'leave_request' ? 'selected' : '' }}>Leave Request</option>
                            <option value="advance_request" {{ old('type') == 'advance_request' ? 'selected' : '' }}>Advance Request</option>
                            <option value="expense_claim" {{ old('type') == 'expense_claim' ? 'selected' : '' }}>Expense Claim</option>
                            <option value="service_request" {{ old('type') == 'service_request' ? 'selected' : '' }}>Service Request</option>
                            <option value="equipment_request" {{ old('type') == 'equipment_request' ? 'selected' : '' }}>Equipment Request</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                        <select name="priority" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency *</label>
                        <select name="currency" required
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="ZMW" {{ old('currency', 'ZMW') == 'ZMW' ? 'selected' : '' }}>ZMW - Zambian Kwacha</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            {{-- <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                            <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                            <option value="JPY" {{ old('currency') == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option> --}}
                        </select>
                        @error('currency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request Date</label>
                        <input type="date"
                               readonly
                               value="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 bg-gray-50 cursor-not-allowed">
                        <input type="hidden" name="request_date" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                {{-- Additional Metadata --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="operational" {{ old('category') == 'operational' ? 'selected' : '' }}>Operational</option>
                            <option value="capital" {{ old('category') == 'capital' ? 'selected' : '' }}>Capital</option>
                            <option value="personnel" {{ old('category') == 'personnel' ? 'selected' : '' }}>Personnel</option>
                            <option value="travel" {{ old('category') == 'travel' ? 'selected' : '' }}>Travel</option>
                            <option value="supplies" {{ old('category') == 'supplies' ? 'selected' : '' }}>Supplies</option>
                            <option value="services" {{ old('category') == 'services' ? 'selected' : '' }}>Services</option>
                            <option value="utilities" {{ old('category') == 'utilities' ? 'selected' : '' }}>Utilities</option>
                            <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Required By Date</label>
                        <input type="date" name="required_by_date"
                               value="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method"
                                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>

                            <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                            <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ========================= --}}
            {{-- REQUEST ITEMS --}}
            {{-- ========================= --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Request Items</h3>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500" id="totalItemsCounter">0 items</span>
                        <button type="button"
                                onclick="addItem()"
                                class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Item
                        </button>
                    </div>
                </div>

                <div class="border rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-3 text-left font-medium text-gray-700">Item Name *</th>
                                    <th class="p-3 text-left font-medium text-gray-700">Description</th>
                                    <th class="p-3 text-center font-medium text-gray-700 w-24">Quantity</th>
                                    <th class="p-3 text-center font-medium text-gray-700 w-32">Unit Price</th>
                                    <th class="p-3 text-center font-medium text-gray-700 w-20">Tax %</th>
                                    <th class="p-3 text-right font-medium text-gray-700 w-32">Line Total</th>
                                    <th class="p-3 w-20"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Items will be added here dynamically -->
                            </tbody>
                            <tfoot class="bg-gray-50 border-t">
                                <tr>
                                    <td colspan="5" class="p-3 text-right font-medium text-gray-700">Subtotal</td>
                                    <td class="p-3 text-right font-medium text-gray-900" id="subtotalDisplay">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="p-3 text-right font-medium text-gray-700">Total Tax</td>
                                    <td class="p-3 text-right font-medium text-gray-900" id="taxDisplay">0.00</td>
                                    <td></td>
                                </tr>
                                <tr class="border-t">
                                    <td colspan="5" class="p-3 text-right font-semibold text-gray-900">Grand Total</td>
                                    <td class="p-3 text-right font-semibold text-blue-700 text-lg" id="grandTotalDisplay">0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Default Tax Rate -->
                <div class="mt-3 flex items-center gap-3">
                    <label class="text-sm text-gray-600">Apply default tax rate to all items:</label>
                    <div class="flex items-center">
                        <input type="number" id="defaultTaxRate" min="0" max="100" step="0.1" readonly value="0"
                               class="w-20 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-right">
                        <span class="ml-2 text-sm text-gray-500">%</span>
                        <button type="button" onclick="applyDefaultTax()"
                                class="ml-4 px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                            Apply
                        </button>
                    </div>
                </div>
            </div>

            {{-- ========================= --}}
            {{-- ATTACHMENTS --}}
            {{-- ========================= --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Supporting Documents</h3>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>

                        <div class="mt-4">
                            <label for="supporting_documents" class="cursor-pointer">
                                <span class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Choose Files
                                </span>
                                <input id="supporting_documents"
                                       name="supporting_documents[]"
                                       type="file"
                                       multiple
                                       class="sr-only"
                                       onchange="updateFileList()">
                            </label>
                            <p class="mt-2 text-sm text-gray-500">or drag and drop files here</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, PDF, DOC, XLS up to 10MB each</p>
                        </div>
                    </div>

                    <!-- Selected Files List -->
                    <div id="fileList" class="mt-4 space-y-2 hidden">
                        <h4 class="text-sm font-medium text-gray-700">Selected Files:</h4>
                        <ul id="selectedFiles" class="space-y-1"></ul>
                    </div>
                </div>
            </div>

            {{-- ========================= --}}
            {{-- ADDITIONAL INFORMATION --}}
            {{-- ========================= --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project/Tender Reference</label>
                        <input type="text" name="related_project" value="{{ old('related_project') }}"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Optional project or tender reference">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Any additional information or special instructions">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- ========================= --}}
            {{-- FORM ACTIONS --}}
            {{-- ========================= --}}
            <div class="pt-6 border-t">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <span id="totalItemsText">0 items</span> • Total Amount:
                        <span class="font-semibold text-gray-900" id="totalAmountText">0.00</span>
                        <input type="hidden" name="amount" id="totalAmountInput" value="0">
                        <input type="hidden" name="line_items_json" id="lineItemsJson">
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('requests.index') }}"
                           class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>

                        <button type="button" onclick="saveAsDraft()"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save as Draft
                        </button>

                        <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit Request
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        let itemIndex = 0;
        let lineItems = [];

        function addItem() {
            const tbody = document.getElementById('itemsBody');
            const row = document.createElement('tr');
            row.className = 'item-row hover:bg-gray-50';
            row.id = `item-${itemIndex}`;

            row.innerHTML = `
                <td class="p-3">
                    <input type="text"
                           name="items[${itemIndex}][name]"
                           required
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Item name"
                           onchange="updateLineItem(${itemIndex})">
                </td>
                <td class="p-3">
                    <input type="text"
                           name="items[${itemIndex}][description]"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Item description"
                           onchange="updateLineItem(${itemIndex})">
                </td>
                <td class="p-3">
                    <input type="number"
                           min="0.01"
                           step="0.01"
                           value="1"
                           name="items[${itemIndex}][quantity]"
                           oninput="calculateLineTotal(${itemIndex})"
                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-center">
                </td>
                <td class="p-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500"></span>
                        </div>
                        <input type="number"
                               step="0.01"
                               min="0"
                               value="0.00"
                               name="items[${itemIndex}][unit_price]"
                               oninput="calculateLineTotal(${itemIndex})"
                               class="pl-7 w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </td>
                <td class="p-3">
                    <div class="relative">
                        <input type="number"
                               min="0"
                               max="100"
                               step="0.1"
                               readonly
                               value="0"
                               name="items[${itemIndex}][tax_rate]"
                               oninput="calculateLineTotal(${itemIndex})"
                               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-center">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">%</span>
                        </div>
                    </div>
                </td>
                <td class="p-3 text-right">
                    <span class="font-medium line-total" data-item="${itemIndex}">0.00</span>
                    <input type="hidden" name="items[${itemIndex}][total]" value="0">
                </td>
                <td class="p-3 text-center">
                    <button type="button"
                            onclick="removeItem(${itemIndex})"
                            class="text-red-600 hover:text-red-800 p-1 rounded-full hover:bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </td>
            `;

            tbody.insertBefore(row, tbody.querySelector('tfoot'));
            itemIndex++;

            // Initialize line item
            lineItems[itemIndex - 1] = {
                name: '',
                description: '',
                quantity: 1,
                unit_price: 0,
                tax_rate: 16,
                total: 0
            };

            calculateLineTotal(itemIndex - 1);
            updateSummary();
        }

        function removeItem(index) {
            const row = document.getElementById(`item-${index}`);
            if (row) {
                row.remove();
                delete lineItems[index];
                updateSummary();
            }

            // Show empty state if no items
            const items = document.querySelectorAll('.item-row');
            if (items.length === 0) {
                updateSummary();
            }
        }

        function calculateLineTotal(index) {
            const row = document.getElementById(`item-${index}`);
            if (!row) return;

            const quantity = parseFloat(row.querySelector('[name*="[quantity]"]').value) || 0;
            const unitPrice = parseFloat(row.querySelector('[name*="[unit_price]"]').value) || 0;
            const taxRate = parseFloat(row.querySelector('[name*="[tax_rate]"]').value) || 0;

            const subtotal = quantity * unitPrice;
            const taxAmount = subtotal * (taxRate / 100);
            const total = subtotal + taxAmount;

            // Update display
            const totalElement = row.querySelector('.line-total');
            const totalInput = row.querySelector('[name*="[total]"]');

            totalElement.textContent = total.toFixed(2);
            totalInput.value = total.toFixed(2);

            // Update lineItems array
            if (lineItems[index]) {
                lineItems[index] = {
                    ...lineItems[index],
                    name: row.querySelector('[name*="[name]"]').value,
                    description: row.querySelector('[name*="[description]"]').value,
                    quantity: quantity,
                    unit_price: unitPrice,
                    tax_rate: taxRate,
                    total: total
                };
            }

            updateSummary();
        }

        function updateLineItem(index) {
            const row = document.getElementById(`item-${index}`);
            if (row && lineItems[index]) {
                lineItems[index].name = row.querySelector('[name*="[name]"]').value;
                lineItems[index].description = row.querySelector('[name*="[description]"]').value;
            }
        }

        function updateSummary() {
            let subtotal = 0;
            let totalTax = 0;
            let grandTotal = 0;
            let itemCount = 0;

            // Calculate from lineItems array
            Object.values(lineItems).forEach(item => {
                if (item) {
                    const itemSubtotal = item.quantity * item.unit_price;
                    const itemTax = itemSubtotal * (item.tax_rate / 100);

                    subtotal += itemSubtotal;
                    totalTax += itemTax;
                    grandTotal += item.total;
                    itemCount += 1;
                }
            });

            // Update displays
            document.getElementById('totalItemsCounter').textContent = `${itemCount} item${itemCount !== 1 ? 's' : ''}`;
            document.getElementById('totalItemsText').textContent = `${itemCount} item${itemCount !== 1 ? 's' : ''}`;
            document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2);
            document.getElementById('taxDisplay').textContent = totalTax.toFixed(2);
            document.getElementById('grandTotalDisplay').textContent = grandTotal.toFixed(2);
            document.getElementById('totalAmountText').textContent = grandTotal.toFixed(2);
            document.getElementById('totalAmountInput').value = grandTotal.toFixed(2);

            // Update line items JSON
            const validLineItems = Object.values(lineItems).filter(item => item && item.name);
            document.getElementById('lineItemsJson').value = JSON.stringify(validLineItems);
        }

        function applyDefaultTax() {
            const defaultTax = parseFloat(document.getElementById('defaultTaxRate').value) || 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const taxInput = row.querySelector('[name*="[tax_rate]"]');
                if (taxInput) {
                    taxInput.value = defaultTax;
                    const index = parseInt(row.id.replace('item-', ''));
                    calculateLineTotal(index);
                }
            });
        }

        function updateFileList() {
            const fileInput = document.getElementById('supporting_documents');
            const fileList = document.getElementById('fileList');
            const selectedFiles = document.getElementById('selectedFiles');

            if (fileInput.files.length > 0) {
                fileList.classList.remove('hidden');
                selectedFiles.innerHTML = '';

                Array.from(fileInput.files).forEach((file, index) => {
                    const listItem = document.createElement('li');
                    listItem.className = 'flex items-center justify-between text-sm';
                    listItem.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="truncate max-w-xs">${file.name}</span>
                            <span class="ml-2 text-gray-500">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                        </div>
                        <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-800">
                            Remove
                        </button>
                    `;
                    selectedFiles.appendChild(listItem);
                });
            } else {
                fileList.classList.add('hidden');
            }
        }

        function removeFile(index) {
            const dt = new DataTransfer();
            const fileInput = document.getElementById('supporting_documents');

            // Add all files except the one to remove
            Array.from(fileInput.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });

            fileInput.files = dt.files;
            updateFileList();
        }

        function saveAsDraft() {
            const form = document.querySelector('form');
            const draftField = document.createElement('input');
            draftField.type = 'hidden';
            draftField.name = 'save_as_draft';
            draftField.value = '1';
            form.appendChild(draftField);
            form.submit();
        }

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {


            // Add first item
            addItem();

            // Handle drag and drop for files
            const dropZone = document.querySelector('.border-dashed');
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');

                const fileInput = document.getElementById('supporting_documents');
                const dt = new DataTransfer();

                // Add existing files
                for (let file of fileInput.files) {
                    dt.items.add(file);
                }

                // Add dropped files
                for (let file of e.dataTransfer.files) {
                    dt.items.add(file);
                }

                fileInput.files = dt.files;
                updateFileList();
            });

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                // Validate at least one item
                const items = document.querySelectorAll('.item-row');
                if (items.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one item to the request.');
                    return;
                }

                // Validate item names
                let isValid = true;
                document.querySelectorAll('[name*="[name]"]').forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('border-red-500');
                        input.focus();
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required item names.');
                }
            });
        });
    </script>
    @endpush
</x-layouts.dashboard>
