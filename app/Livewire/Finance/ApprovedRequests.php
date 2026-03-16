<?php

namespace App\Livewire\Finance;

use App\Models\Request;

class ApprovedRequests extends BaseRequestComponent
{
    protected function getStatus()
    {
        return 'manager_approved';
    }

    public function markAsPaid($requestId)
    {
        $request = Request::find($requestId);
        $oldStatus = $request->status;
        dd($request);
        $request->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        // Dispatch event to all listening components
        $this->dispatch('requestStatusUpdated',
            requestId: $requestId,
            newStatus: 'approved',
            previousStatus: $oldStatus
        );

        session()->flash('message', 'Request marked as approved.');
    }
}
