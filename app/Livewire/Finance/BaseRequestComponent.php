<?php

namespace App\Livewire\Finance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Request;

abstract class BaseRequestComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function dispatchAction($action, $requestId)
    {
         dd($action);
        match ($action) {


            'markAsPaid' => $this->dispatch('markAsPaid', id: $requestId)
                        ->to(\App\Livewire\Finance\ApprovedRequests::class),

            default => null,
        };
    }

    abstract protected function getStatus();
    // Listen for events from other components
    protected function getListeners()
    {
        return [
            'refreshComponent' => '$refresh',
            'requestStatusUpdated' => 'handleStatusUpdate',
        ];
    }

    public function handleStatusUpdate($requestId, $newStatus, $previousStatus)
    {
        // Refresh the component if it should show this request
        if ($this->getStatus() === $newStatus || $this->getStatus() === $previousStatus) {
            $this->dispatch('$refresh');
        }
    }
    public function updatedSearch()
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

    public function render()
    {
        $requests = Request::where('status', $this->getStatus())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('request_number', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%')
                                  ->orWhere('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.finance.base-request-component', [
            'requests' => $requests,
            'status' => $this->getStatus()
        ]);
    }
}
