<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RequestController extends Controller
{

public function index(HttpRequest $request)
{
    // Get current user
    $user = auth::user();

    // Base query
    $query = Request::where('user_id', $user->id);

    // Apply status filter if provided
    // dd($request->status);
    if ($request->status) {
        switch ($request->status) {
            case 'draft':
                $query->where('status', 'draft');
                break;
            case 'pending':
                $query->whereIn('status', ['submitted', 'junior_approved', 'senior_approved', 'payment_processing', 'on_hold']);
                break;
            case 'approved':
                $query->whereIn('status', ['manager_approved', 'paid']);
                break;
            case 'rejected':
                $query->whereIn('status', ['rejected']);
                break;
            default:
                // You can add more specific status filters if needed
                $query->where('status', $request->status);
        }
    }
          // Get incomplete requests for "In Progress" tab
        if ($user->hasRole('Super Admin')) {
            $inProgressRequests = Request::whereIn('status', [
                'draft',
                'submitted',
                'junior_approved',
                'senior_approved',
                'payment_processing'
            ])->orderBy('priority', 'desc')
                ->orderBy('required_by_date', 'asc')
                ->get();
        } else {
            $inProgressRequests = Request::whereIn('status', [
                'draft',
                'submitted',
                'junior_approved',
                'senior_approved',
                'payment_processing'
            ])->where('user_id', $user->id)
                ->orderBy('priority', 'desc')
                ->orderBy('required_by_date', 'asc')
                ->get();
        }

    // Get paginated requests
    $myRequests = $query->latest()->paginate(10);

    // Calculate stats (this should be optimized for production)
    $stats = [
        'draft' => Request::where('user_id', $user->id)
                    ->where('status', 'draft')->count(),
        'pending' => Request::where('user_id', $user->id)
                    ->whereIn('status', ['submitted', 'junior_approved', 'senior_approved', 'payment_processing', 'on_hold'])->count(),
        'approved' => Request::where('user_id', $user->id)
                    ->whereIn('status', ['manager_approved', 'paid'])->count(),
        'rejected' => Request::where('user_id', $user->id)
                    ->whereIn('status', ['rejected'])->count(),
    ];

    return view('requests.index', compact('myRequests', 'inProgressRequests', 'stats'));
}
    protected function getStatusGroup($status)
    {
        $statusGroups = [
            'draft' => ['draft'],
            'pending' => ['submitted', 'junior_approved', 'senior_approved', 'payment_processing', 'on_hold'],
            'approved' => ['manager_approved', 'paid'],
            'rejected' => ['junior_rejected', 'senior_rejected', 'manager_rejected', 'cancelled'],
        ];

        foreach ($statusGroups as $group => $statuses) {
            if (in_array($status, $statuses)) {
                return $group;
            }
        }

        return 'other';
    }

    public function create()
    {
        return view('requests.create');
    }
    public function store(HttpRequest $request)
    {

        // Validate the request
        $validated = $request->validate([
            'type' => ['required', Rule::in([
                'payment_request',
                'purchase_request',
                'travel_request',
                'leave_request',
                'advance_request',
                'expense_claim',
                'service_request',
                'equipment_request',
                'other'
            ])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'currency' => ['required', 'string', 'size:3'],
            'category' => ['nullable', Rule::in([
                'operational',
                'capital',
                'personnel',
                'travel',
                'supplies',
                'services',
                'utilities',
                'maintenance',
                'other'
            ])],
            'payment_method' => ['nullable', Rule::in([
                'bank_transfer',
                'check',
                'cash',
                'credit_card',
                'debit_card',
                'mobile_money',
                'other'
            ])],
            'budget_code' => ['nullable', 'string', 'max:50'],
            'required_by_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'related_project' => ['nullable', 'string', 'max:255'],
            'supporting_documents' => ['nullable', 'array'],
            'supporting_documents.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.total' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            // Generate unique request number
            $requestNumber = $this->generateRequestNumber();

            // Handle file uploads
            $supportingDocuments = [];
            if ($request->hasFile('supporting_documents')) {
                foreach ($request->file('supporting_documents') as $file) {
                    $path = $file->store('requests/supporting-documents', 'public');
                    $supportingDocuments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }

            // Process line items
            $lineItems = [];
            $totalAmount = 0;

            foreach ($request->input('items') as $index => $item) {
                $quantity = floatval($item['quantity']);
                $unitPrice = floatval($item['unit_price']);
                $taxRate = floatval($item['tax_rate'] ?? 0);
                $subtotal = $quantity * $unitPrice;
                $taxAmount = $subtotal * ($taxRate / 100);
                $total = floatval($item['total']);

                $lineItems[] = [
                    'id' => $index + 1,
                    'name' => $item['name'],
                    'description' => $item['description'] ?? '',
                    'quantity' => $quantity,
                    'unit' => 'pcs', // Default unit
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'subtotal' => $subtotal,
                    'total' => $total,
                ];

                $totalAmount += $total;
            }

            // Determine status
            $status = $request->has('save_as_draft') ? 'draft' : 'submitted';

            // Create the request
            try {
                $newRequest = Request::create([
                    'user_id' => Auth::id(),
                    'request_number' => $requestNumber,
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'type' => $validated['type'],
                    'priority' => $validated['priority'],
                    'status' => $status,
                    'amount' => $totalAmount,
                    'currency' => $validated['currency'],
                    'payment_method' => $validated['payment_method'] ?? null,
                    'category' => $validated['category'] ?? 'operational',
                    'budget_code' => $validated['budget_code'] ?? null,
                    'request_date' => now(),
                    'required_by_date' => $validated['required_by_date'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'related_project_id' => $this->getProjectId($validated['related_project'] ?? null),
                    'supporting_documents' => !empty($supportingDocuments) ? $supportingDocuments : null,
                    'line_items' => $lineItems,
                    'sla_days' => 14,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'An error occurred while creating the request: ' . $e->getMessage());
            }

            return redirect()->route('requests.index')
                ->with('success', $status === 'draft'
                    ? 'Request saved as draft successfully!'
                    : 'Request submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the request: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique request number
     */
    private function generateRequestNumber()
    {
        $prefix = 'REQ';
        $year = date('Y');
        $month = date('m');

        // Get last request number for this month
        $lastRequest = Request::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastRequest && preg_match('/REQ-' . $year . $month . '-(\d+)/', $lastRequest->request_number, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }

        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $sequence);
    }

    /**
     * Get project ID from reference (simplified - you can implement your own logic)
     */
    private function getProjectId($reference)
    {
        if (!$reference) {
            return null;
        }

        // You can implement your own logic here to find project by reference
        // For now, return null
        return null;
    }

    public function show(Request $request)
    {
        return view('requests.show', compact('request'));
    }

public function approve($requestId)
{
    // Find the request
    $requestModel = Request::findOrFail($requestId);

    // Get current user
    $user = auth::user();

    // Get the next status based on current status
    $nextStatus = $this->getNextStatus($requestModel->status, $user);

    // Update request status
    $requestModel->update(['status' => $nextStatus]);

    // ======================================================
    // ADDITIONAL LOGIC: AUDIT TRAIL
    // ======================================================

    // Create audit trail entry
    // AuditTrail::create([
    //     'request_id' => $requestId,
    //     'user_id' => $user->id,
    //     'user_name' => $user->name,
    //     'action' => 'approve',
    //     'details' => "Approved request and changed status from {$requestModel->getOriginal('status')} to {$nextStatus}",
    //     'ip_address' => request()->ip(),
    //     'user_agent' => request()->userAgent(),
    // ]);

    // Alternatively, if you have an activity log package like spatie/laravel-activitylog:
    // activity()
    //     ->performedOn($requestModel)
    //     ->causedBy($user)
    //     ->withProperties(['old_status' => $requestModel->getOriginal('status'), 'new_status' => $nextStatus])
    //     ->log('approved');

    // ======================================================
    // ADDITIONAL LOGIC: NOTIFICATIONS
    // ======================================================

    // 1. Notify the requester
    // $requester = $requestModel->user;
    // if ($requester) {
    //     Notification::send($requester, new RequestStatusChangedNotification(
    //         request: $requestModel,
    //         action: 'approved',
    //         performedBy: $user,
    //         comments: null
    //     ));

    //     // Or using database notifications
    //     $requester->notify(new RequestApprovedNotification($requestModel, $user));
    // }

    // 2. Notify next approver if applicable
    // $nextApprover = $this->getNextApprover($nextStatus);
    // if ($nextApprover) {
    //     Notification::send($nextApprover, new RequestPendingActionNotification(
    //         request: $requestModel,
    //         action: $nextStatus,
    //         dueDate: $requestModel->required_by_date
    //     ));
    // }

    // 3. Notify managers or supervisors
    // $managers = User::where('role', 'manager')->orWhere('role', 'supervisor')->get();
    // Notification::send($managers, new RequestUpdatedNotification(
    //     request: $requestModel,
    //     action: 'approved',
    //     performer: $user
    // ));

    // 4. Send email notification
    // Mail::to($requester->email)->send(new RequestApprovedMail($requestModel, $user));

    // ======================================================
    // ADDITIONAL LOGIC: STATUS HISTORY
    // ======================================================

    // StatusHistory::create([
    //     'request_id' => $requestId,
    //     'from_status' => $requestModel->getOriginal('status'),
    //     'to_status' => $nextStatus,
    //     'changed_by' => $user->id,
    //     'changed_by_name' => $user->name,
    //     'comments' => null,
    //     'effective_date' => now(),
    // ]);





    // Return with success message
    return redirect()->route('requests.index')->with('success', 'Request approved successfully.');
}

// Helper method to determine next status
private function getNextStatus($currentStatus, $user)
{
    $statusFlow = [
        'draft' => 'submitted',
        'submitted' => 'junior_approved',
        'junior_approved' => 'senior_approved',
        'senior_approved' => 'manager_approved',
        'manager_approved' => 'payment_processing',
    ];

    // Check if user has permission to advance status
    if ($user->role === 'junior_finance' && $currentStatus === 'submitted') {
        return 'junior_approved';
    } elseif ($user->role === 'senior_finance' && $currentStatus === 'junior_approved') {
        return 'senior_approved';
    } elseif ($user->role === 'manager' && $currentStatus === 'senior_approved') {
        return 'manager_approved';
    }


    return $statusFlow[$currentStatus] ?? $currentStatus;
}

// Helper method to get next approver
private function getNextApprover($nextStatus)
{
    $roleMap = [
        'junior_approved' => 'senior_finance',
        'senior_approved' => 'manager',
        'manager_approved' => 'finance',
    ];

    $role = $roleMap[$nextStatus] ?? null;

    if ($role) {
        return User::where('role', $role)->first();
    }

    return null;
}



    public function reject($requestID, HttpRequest $request)
    {
        $requestModel = Request::findOrFail($requestID);
       $reason = $request->input('rejection_reason');
        $requestModel->update(['status' => 'rejected', 'rejection_reason' => $reason]);
        // Add any additional logic
        return redirect()->back()->with('success', 'Request rejected.');
    }

    public function revert(Request $requestModel)
    {
        $requestModel->update(['status' => 'draft']);
        // Add any additional logic
        return redirect()->back()->with('success', 'Request reverted to draft.');
    }
}
