<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Project;
use App\Models\Tender;
use App\Models\Request as UserRequest;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Collection;

class RecentActivities extends Component
{
    public $activities = [];
    public $limit = 5;

    protected $listeners = ['activityAdded' => 'refreshActivities'];

    public function mount()
    {
        $this->loadActivities();
    }

    public function loadActivities()
    {
        // Get activities from different sources
        $projectActivities = Project::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($project) {
                return [
                    'id' => 'project_' . $project->id,
                    'type' => 'project',
                    'title' => $project->name,
                    'description' => 'New project created',
                    'user' => $project->user->name,
                    'user_initials' => strtoupper(substr($project->user->name, 0, 2)),
                    'time' => $project->created_at->diffForHumans(),
                    'icon' => 'folder',
                    'color' => 'blue',
                    'link' => route('projects.show', $project),
                ];
            });

        $tenderActivities = Tender::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($tender) {
                return [
                    'id' => 'tender_' . $tender->id,
                    'type' => 'tender',
                    'title' => $tender->title,
                    'description' => 'Tender published',
                    'user' => $tender->user->name,
                    'user_initials' => strtoupper(substr($tender->user->name, 0, 2)),
                    'time' => $tender->created_at->diffForHumans(),
                    'icon' => 'shield',
                    'color' => 'emerald',
                    'link' => route('tenders.show', $tender),
                ];
            });

        $requestActivities = UserRequest::with('requester')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($request) {
                return [
                    'id' => 'request_' . $request->id,
                    'type' => 'request',
                    'title' => $request->title,
                    'description' => 'New request submitted',
                    'user' => User::where('id', $request->user_id)->first()->name ?? 'Unknown User',
                    'user_initials' => strtoupper(substr(User::where('id', $request->user_id)->first()->name ?? 'Unknown User', 0, 2)),
                    'time' => $request->created_at->diffForHumans(),
                    'icon' => 'message',
                    'color' => 'purple',
                    'link' => route('requests.show', $request),
                ];
            });

        // Merge and sort all activities
        $this->activities =$requestActivities
            ->sortByDesc(function($activity) {
                return strtotime($activity['time']);
            })
            ->take($this->limit)
            ->values()
            ->toArray();

        // If no activities, show sample data
        if (empty($this->activities)) {
            $this->activities = $this->getSampleActivities();
        }
    }

    public function refreshActivities()
    {
        $this->loadActivities();
    }

    public function loadMore()
    {
        $this->limit += 5;
        $this->loadActivities();
    }

    private function getSampleActivities()
    {
        return [
            [
                'id' => 'sample_1',
                'type' => 'project',
                'title' => 'Website Redesign',
                'description' => 'New project created',
                'user' => 'John Doe',
                'user_initials' => 'JD',
                'time' => '2 minutes ago',
                'icon' => 'folder',
                'color' => 'blue',
                'link' => '#',
            ],
            [
                'id' => 'sample_2',
                'type' => 'tender',
                'title' => 'Infrastructure Tender',
                'description' => 'Tender published',
                'user' => 'Jane Smith',
                'user_initials' => 'JS',
                'time' => '1 hour ago',
                'icon' => 'shield',
                'color' => 'emerald',
                'link' => '#',
            ],
            [
                'id' => 'sample_3',
                'type' => 'request',
                'title' => 'Marketing Payment',
                'description' => 'Payment request submitted',
                'user' => 'Mike Johnson',
                'user_initials' => 'MJ',
                'time' => '3 hours ago',
                'icon' => 'message',
                'color' => 'purple',
                'link' => '#',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.recent-activities');
    }
}
