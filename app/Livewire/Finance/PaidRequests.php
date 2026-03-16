<?php

namespace App\Livewire\Finance;

use App\Models\Request;
use Livewire\WithPagination;
use Livewire\Component;
use Carbon\Carbon;

class PaidRequests extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;
    public $sortField = 'paid_at';
    public $sortDirection = 'desc';
    public $dateFilter = 'all';

    protected $listeners = ['refreshPaidRequests' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }


    public function downloadReceipt($requestId)
    {
        $request = Request::find($requestId);
        // Implement receipt download logic
        return redirect()->route('finance.receipt', $request);
    }

    public function exportPaid()
    {
        // Implement export logic
        $this->dispatch('exportPaidRequests');
    }

    public function getTotalPaidAmountProperty()
    {
        return Request::where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->sum('amount');
    }

    public function getMonthlyPaidAmountProperty()
    {
        return Request::where('status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->sum('amount');
    }

    public function getAvgProcessingTimeProperty()
    {
        return Request::where('status', 'paid')
            ->whereNotNull('third_approved_at')
            ->whereNotNull('paid_at')
            ->get()
            ->avg(function ($request) {
                return $request->third_approved_at->diffInDays($request->paid_at);
            }) ?? 0;
    }

    public function render()
    {
        $query = Request::with(['user'])
            ->where('status', 'paid');

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('request_number', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($userQuery) {
                      $userQuery->where('name', 'like', '%' . $this->search . '%')
                              ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply date filter
        if ($this->dateFilter === 'today') {
            $query->whereDate('paid_at', Carbon::today());
        } elseif ($this->dateFilter === 'week') {
            $query->whereBetween('paid_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->dateFilter === 'month') {
            $query->whereMonth('paid_at', Carbon::now()->month)
                  ->whereYear('paid_at', Carbon::now()->year);
        } elseif ($this->dateFilter === 'year') {
            $query->whereYear('paid_at', Carbon::now()->year);
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.finance.paid-requests', [
            'requests' => $query->paginate($this->perPage),
            'totalPaidAmount' => $this->totalPaidAmount,
            'monthlyPaidAmount' => $this->monthlyPaidAmount,
            'avgProcessingTime' => $this->avgProcessingTime
        ]);
    }
}
