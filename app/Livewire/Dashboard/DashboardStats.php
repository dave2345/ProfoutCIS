<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Project;
use App\Models\Tender;
use App\Models\Request as UserRequest;
use App\Models\Certificate;
use Illuminate\Support\Facades\Cache;

class DashboardStats extends Component
{
    public $totalProjects = 0;
    public $totalTenders = 0;
    public $totalRequests = 0;
    public $totalCertificates = 0;
    public $activeProjects = 0;
    public $openTenders = 0;
    public $pendingApprovals = 0;
    public $expiringCertificates = 0;
    public $projectCompletionRate = 0;
    public $tenderSuccessRate = 0;

    protected $listeners = ['refreshDashboardStats' => 'refreshStats'];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // Cache stats for 5 minutes for better performance
        $this->totalProjects = Cache::remember('total_projects', 300, function() {
            return Project::count();
        });

        $this->totalTenders = Cache::remember('total_tenders', 300, function() {
            return Tender::count();
        });

        $this->totalRequests = Cache::remember('total_requests', 300, function() {
            return UserRequest::count();
        });

        $this->totalCertificates = Cache::remember('total_certificates', 300, function() {
            return Certificate::count();
        });

        $this->activeProjects = Cache::remember('active_projects', 300, function() {
            return Project::where('status', 'in_progress')->count();
        });

        $this->openTenders = Cache::remember('open_tenders', 300, function() {
            return Tender::where('status', 'open')->count();
        });

        $this->pendingApprovals = Cache::remember('pending_approvals', 300, function() {
            return UserRequest::whereIn('status', ['submitted', 'junior_approved', 'senior_approved'])->count();
        });

        $this->expiringCertificates = Cache::remember('expiring_certificates', 300, function() {
            return Certificate::where('expiry_date', '<=', now()->addDays(30))
                ->where('status', 'active')
                ->count();
        });

        // Calculate rates
        $completedProjects = Project::where('status', 'completed')->count();
        $totalProjects = $this->totalProjects ?: 1;
        $this->projectCompletionRate = round(($completedProjects / $totalProjects) * 100);

        $awardedTenders = Tender::where('status', 'awarded')->count();
        $totalTenders = $this->totalTenders ?: 1;
        $this->tenderSuccessRate = round(($awardedTenders / $totalTenders) * 100);
    }

    public function refreshStats()
    {
        Cache::forget('total_projects');
        Cache::forget('total_tenders');
        Cache::forget('total_requests');
        Cache::forget('total_certificates');
        Cache::forget('active_projects');
        Cache::forget('open_tenders');
        Cache::forget('pending_approvals');
        Cache::forget('expiring_certificates');

        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-stats');
    }
}
