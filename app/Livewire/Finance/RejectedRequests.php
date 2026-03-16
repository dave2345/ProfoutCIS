<?php

namespace App\Livewire\Finance;

use App\Models\Request;

class RejectedRequests extends BaseRequestComponent
{
    protected function getStatus()
    {
        return 'rejected';
    }

    public function reopenRequest($requestId)
    {
        $request = Request::find($requestId);
        dd($request);
        $request->update(['status' => 'pending']);
        session()->flash('message', 'Request reopened.');
    }
}
