<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use App\Models\Request;
use App\Models\User;
use Carbon\Carbon;

class RequestReport extends Component
{
    public $reportType = 'day';
    public $selectedUsers = [];
    public $selectAll = false;
    public $dateRange = 'today';
    public $customStartDate;
    public $customEndDate;
    public $reportData = null;

    protected $listeners = ['generateReport', 'exportReport'];

    public function mount()
    {
        $this->selectedUsers = [];
        $this->customStartDate = now()->format('Y-m-d');
        $this->customEndDate = now()->format('Y-m-d');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = User::pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function generateReport()
    {
        $query = Request::query();

        // Apply date filter
        switch ($this->reportType) {
            case 'day':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom':
                if ($this->customStartDate && $this->customEndDate) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($this->customStartDate)->startOfDay(),
                        Carbon::parse($this->customEndDate)->endOfDay()
                    ]);
                }
                break;
        }

        // Apply user filter
        if (!empty($this->selectedUsers)) {
            $query->whereIn('user_id', $this->selectedUsers);
        }

        // Generate report data
        $this->reportData = [
            'total_requests' => $query->count(),
            'by_status' => [
                'pending' => (clone $query)->where('status', 'pending')->count(),
                'approved' => (clone $query)->where('status', 'approved')->count(),
                'paid' => (clone $query)->where('status', 'paid')->count(),
                'rejected' => (clone $query)->where('status', 'rejected')->count(),
            ],
            'total_amount' => $query->sum('amount'),
            'requests' => $query->with('user')->orderBy('created_at', 'desc')->get(),
            'date_range' => $this->getDateRangeLabel(),
            'generated_at' => now()->format('Y-m-d H:i:s')
        ];

        $this->dispatch('reportGenerated', $this->reportData);
    }

    public function exportReport($format = 'pdf')
    {
        if (!$this->reportData) {
            $this->generateReport();
        }

        return redirect()->route('finance.reports.export', [
            'format' => $format,
            'data' => encrypt($this->reportData)
        ]);
    }

    private function getDateRangeLabel()
    {
        switch ($this->reportType) {
            case 'day':
                return 'Today (' . now()->format('Y-m-d') . ')';
            case 'week':
                return 'Week ' . now()->weekOfYear . ' (' . now()->startOfWeek()->format('Y-m-d') . ' to ' . now()->endOfWeek()->format('Y-m-d') . ')';
            case 'month':
                return now()->format('F Y');
            case 'year':
                return now()->format('Y');
            case 'custom':
                return $this->customStartDate . ' to ' . $this->customEndDate;
            default:
                return 'Custom Range';
        }
    }

    public function render()
    {
        $users = User::orderBy('name')->get();

        return view('livewire.finance.request-report', [
            'users' => $users
        ]);
    }
}
