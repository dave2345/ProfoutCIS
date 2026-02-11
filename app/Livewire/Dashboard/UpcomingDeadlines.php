<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Project;
use App\Models\Tender;
use Illuminate\Support\Carbon;

class UpcomingDeadlines extends Component
{
    public $deadlines = [];
    public $daysAhead = 30;

    protected $listeners = ['deadlineUpdated' => 'refreshDeadlines'];

    public function mount()
    {
        $this->loadDeadlines();
    }

    public function loadDeadlines()
    {
        $projectDeadlines = Project::whereNotNull('deadline')
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays($this->daysAhead))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('deadline')
            ->get()
            ->map(function ($project) {
                $daysRemaining = now()->diffInDays($project->deadline, false);

                return [
                    'id' => 'project_' . $project->id,
                    'type' => 'project',
                    'title' => $project->name,
                    'deadline' => $project->deadline,
                    'formatted_deadline' => $project->deadline->format('M d, Y'),
                    'status' => $project->status,
                    'days_remaining' => $daysRemaining,
                    'color' => $this->getDeadlineColor($daysRemaining),
                    'priority' => $project->priority,
                    'link' => route('projects.show', $project),
                ];
            });

        $tenderDeadlines = Tender::whereNotNull('submission_deadline')
            ->where('submission_deadline', '>=', now())
            ->where('submission_deadline', '<=', now()->addDays($this->daysAhead))
            ->whereNotIn('status', ['closed', 'cancelled', 'awarded'])
            ->orderBy('submission_deadline')
            ->get()
            ->map(function ($tender) {
                $daysRemaining = now()->diffInDays($tender->submission_deadline, false);

                return [
                    'id' => 'tender_' . $tender->id,
                    'type' => 'tender',
                    'title' => $tender->title,
                    'deadline' => $tender->submission_deadline,
                    'formatted_deadline' => $tender->submission_deadline->format('M d, Y'),
                    'status' => $tender->status,
                    'days_remaining' => $daysRemaining,
                    'color' => $this->getDeadlineColor($daysRemaining),
                    'estimated_value' => $tender->estimated_value,
                    'link' => route('tenders.show', $tender),
                ];
            });

        // Merge deadlines and sort by date
        $this->deadlines = $projectDeadlines
            ->merge($tenderDeadlines)
            ->sortBy('deadline')
            ->values()
            ->toArray();

        // If no deadlines, show sample data
        if (empty($this->deadlines)) {
            $this->deadlines = $this->getSampleDeadlines();
        }
    }

    public function refreshDeadlines()
    {
        $this->loadDeadlines();
    }

    private function getDeadlineColor($daysRemaining)
    {
        if ($daysRemaining < 0) {
            return 'red';
        } elseif ($daysRemaining <= 3) {
            return 'orange';
        } elseif ($daysRemaining <= 7) {
            return 'yellow';
        } else {
            return 'green';
        }
    }

    private function getSampleDeadlines()
    {
        return [
            [
                'id' => 'sample_1',
                'type' => 'project',
                'title' => 'E-commerce Platform',
                'deadline' => now()->addDays(2),
                'formatted_deadline' => now()->addDays(2)->format('M d, Y'),
                'status' => 'in_progress',
                'days_remaining' => 2,
                'color' => 'red',
                'priority' => 'high',
                'link' => '#',
            ],
            [
                'id' => 'sample_2',
                'type' => 'tender',
                'title' => 'Infrastructure Tender',
                'deadline' => now()->addDays(5),
                'formatted_deadline' => now()->addDays(5)->format('M d, Y'),
                'status' => 'open',
                'days_remaining' => 5,
                'color' => 'orange',
                'estimated_value' => 500000,
                'link' => '#',
            ],
        ];
    }

    public function updateDaysAhead($days)
    {
        $this->daysAhead = $days;
        $this->loadDeadlines();
    }

    public function render()
    {
        return view('livewire.dashboard.upcoming-deadlines');
    }
}
