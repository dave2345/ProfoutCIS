<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tender;
use App\Models\Request as UserRequest;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Get statistics for the dashboard
        $stats = [
            'total_projects' => Project::count(),
            'total_tenders' => Tender::count(),
            'total_requests' => UserRequest::count(),
            'total_certificates' => Certificate::count(),
        ];

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        // Get upcoming deadlines
        $upcomingDeadlines = $this->getUpcomingDeadlines();

        // Get projects by status
        $projectStatus = $this->getProjectStatus();

        // Get tender statistics
        $tenderStats = $this->getTenderStats();

        // Get monthly growth data
        $monthlyData = $this->getMonthlyData();

        // Get user-specific statistics if needed
        $userStats = $this->getUserStats();

        return view('dashboard', compact(
            'stats',
            'recentActivities',
            'upcomingDeadlines',
            'projectStatus',
            'tenderStats',
            'monthlyData',
            'userStats'
        ));
    }

    /**
     * Get recent activities across all modules.
     */
    private function getRecentActivities()
    {
        // // Combine recent activities from different models
        $projectActivities = Project::with('user')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($project) {
                return [
                    'type' => 'project',
                    'title' => $project->name,
                    'description' => 'Project created',
                    'user' => $project->user->name,
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
                    'type' => 'tender',
                    'title' => $tender->title,
                    'description' => 'Tender published',
                    'user' => $tender->user->name,
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
                    'type' => 'request',
                    'title' => $request->subject,
                    'description' => 'New request submitted',
                   'user' => User::where('id', $request->user_id)->first()->name ?? 'Unknown User',
                    'time' => $request->created_at->diffForHumans(),
                    'icon' => 'message',
                    'color' => 'purple',
                    'link' => route('requests.show', $request),
                ];
            });

        // Merge all activities and sort by time
        // $activities = $projectActivities
        //     ->merge($tenderActivities)
        //     ->merge($requestActivities)
        //     ->sortByDesc('time')
        //     ->take(8);
        $activities = $requestActivities
            ->sortByDesc('time')
            ->take(8);
        return $activities->values();
    }

    /**
     * Get upcoming deadlines for projects and tenders.
     */
    private function getUpcomingDeadlines()
    {
        $projectDeadlines = Project::whereNotNull('deadline')
            ->where('deadline', '>=', now())
            ->where('deadline', '<=', now()->addDays(30))
            ->orderBy('deadline')
            ->take(5)
            ->get()
            ->map(function ($project) {
                return [
                    'type' => 'project',
                    'title' => $project->name,
                    'deadline' => $project->deadline,
                    'status' => $project->status,
                    'days_remaining' => now()->diffInDays($project->deadline),
                    'color' => $this->getDeadlineColor($project->deadline),
                    'link' => route('projects.show', $project),
                ];
            });

        $tenderDeadlines = Tender::whereNotNull('submission_deadline')
            ->where('submission_deadline', '>=', now())
            ->where('submission_deadline', '<=', now()->addDays(30))
            ->orderBy('submission_deadline')
            ->take(5)
            ->get()
            ->map(function ($tender) {
                return [
                    'type' => 'tender',
                    'title' => $tender->title,
                    'deadline' => $tender->submission_deadline,
                    'status' => $tender->status,
                    'days_remaining' => now()->diffInDays($tender->submission_deadline),
                    'color' => $this->getDeadlineColor($tender->submission_deadline),
                    'link' => route('tenders.show', $tender),
                ];
            });

        // Merge deadlines and sort by date
        $deadlines = $projectDeadlines
            ->merge($tenderDeadlines)
            ->sortBy('deadline')
            ->take(6);

        return $deadlines->values();
    }

    /**
     * Get project status distribution.
     */
    private function getProjectStatus()
    {
        return Project::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            })
            ->toArray();
    }

    /**
     * Get tender statistics.
     */
    private function getTenderStats()
    {
        return [
            'total' => Tender::count(),
            'open' => Tender::where('status', 'open')->count(),
            'closed' => Tender::where('status', 'closed')->count(),
            'awarded' => Tender::where('status', 'awarded')->count(),
            'average_value' => Tender::avg('estimated_value') ?? 0,
        ];
    }

    /**
     * Get monthly growth data for charts.
     */
    private function getMonthlyData()
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months->push([
                'month' => $month->format('M Y'),
                'projects' => Project::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
                'tenders' => Tender::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
                'requests' => UserRequest::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
            ]);
        }

        return $months;
    }

    /**
     * Get user-specific statistics.
     */
    private function getUserStats()
    {
        $user = auth()->user();

        return [
            'user_projects' => Project::where('user_id', $user->id)->count(),
            'user_tenders' => Tender::where('user_id', $user->id)->count(),
            'user_requests' => UserRequest::where('user_id', $user->id)->count(),
            'recent_assigned' => $this->getUserRecentAssignments($user),
        ];
    }

    /**
     * Get user's recent assignments.
     */
    private function getUserRecentAssignments($user)
    {
        // You can customize this based on your assignment logic
        return [
            'projects' => Project::where('assigned_to', $user->id)
                ->latest()
                ->take(3)
                ->get(),
            'tenders' => Tender::where('assigned_to', $user->id)
                ->latest()
                ->take(3)
                ->get(),
        ];
    }

    /**
     * Get color based on deadline urgency.
     */
    private function getDeadlineColor($deadline)
    {
        $daysRemaining = now()->diffInDays($deadline, false);

        if ($daysRemaining < 0) {
            return 'red'; // Overdue
        } elseif ($daysRemaining <= 3) {
            return 'orange'; // Urgent
        } elseif ($daysRemaining <= 7) {
            return 'yellow'; // Approaching
        } else {
            return 'green'; // On track
        }
    }

    /**
     * Get dashboard widgets configuration.
     */
    public function getWidgets(Request $request)
    {
        $user = auth()->user();
        $widgets = $request->user()->dashboard_widgets ?? config('dashboard.default_widgets');

        return response()->json([
            'widgets' => $widgets,
            'available_widgets' => $this->getAvailableWidgets(),
        ]);
    }

    /**
     * Update dashboard widgets configuration.
     */
    public function updateWidgets(Request $request)
    {
        $validated = $request->validate([
            'widgets' => 'required|array',
            'widgets.*' => 'string',
        ]);

        $request->user()->update([
            'dashboard_widgets' => $validated['widgets'],
        ]);

        return response()->json([
            'message' => 'Dashboard widgets updated successfully',
            'widgets' => $validated['widgets'],
        ]);
    }

    /**
     * Get available dashboard widgets.
     */
    private function getAvailableWidgets()
    {
        return [
            [
                'id' => 'stats_overview',
                'title' => 'Overview Statistics',
                'description' => 'Key statistics and metrics',
                'size' => 'large',
                'default' => true,
            ],
            [
                'id' => 'recent_activities',
                'title' => 'Recent Activities',
                'description' => 'Latest activities across the platform',
                'size' => 'medium',
                'default' => true,
            ],
            [
                'id' => 'upcoming_deadlines',
                'title' => 'Upcoming Deadlines',
                'description' => 'Projects and tenders with approaching deadlines',
                'size' => 'medium',
                'default' => true,
            ],
            [
                'id' => 'project_status',
                'title' => 'Project Status',
                'description' => 'Distribution of projects by status',
                'size' => 'small',
                'default' => true,
            ],
            [
                'id' => 'monthly_growth',
                'title' => 'Monthly Growth',
                'description' => 'Growth trends over the last 6 months',
                'size' => 'large',
                'default' => true,
            ],
            [
                'id' => 'quick_actions',
                'title' => 'Quick Actions',
                'description' => 'Frequently used actions',
                'size' => 'small',
                'default' => false,
            ],
        ];
    }

    /**
     * Get quick actions for the dashboard.
     */
    public function getQuickActions()
    {
        return response()->json([
            'actions' => [
                [
                    'title' => 'Create Project',
                    'description' => 'Start a new project',
                    'icon' => 'folder-plus',
                    'color' => 'blue',
                    'link' => route('projects.create'),
                    'permission' => 'create_project',
                ],
                [
                    'title' => 'Submit Tender',
                    'description' => 'Submit a new tender',
                    'icon' => 'shield-plus',
                    'color' => 'emerald',
                    'link' => route('tenders.create'),
                    'permission' => 'create_tender',
                ],
                [
                    'title' => 'New Request',
                    'description' => 'Submit a new request',
                    'icon' => 'message-square-plus',
                    'color' => 'purple',
                    'link' => route('requests.create'),
                    'permission' => 'create_request',
                ],
                [
                    'title' => 'Generate Certificate',
                    'description' => 'Create a new certificate',
                    'icon' => 'file-certificate',
                    'color' => 'amber',
                    'link' => route('certificates.create'),
                    'permission' => 'create_certificate',
                ],
                [
                    'title' => 'View Reports',
                    'description' => 'View business reports',
                    'icon' => 'pie-chart',
                    'color' => 'indigo',
                    'link' => route('reports.index'),
                    'permission' => 'view_reports',
                ],
                [
                    'title' => 'Manage Team',
                    'description' => 'Team management',
                    'icon' => 'users',
                    'color' => 'pink',
                    'link' => route('team.index'),
                    'permission' => 'manage_team',
                ],
            ],
        ]);
    }

    /**
     * Get notifications for the dashboard.
     */
    public function getNotifications()
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->whereNull('read_at')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'read_at' => $notification->read_at,
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsAsRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Search across the platform.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return response()->json(['results' => []]);
        }

        $results = [
            'projects' => Project::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(5)
                ->get(),
            'tenders' => Tender::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(5)
                ->get(),
            'requests' => UserRequest::where('subject', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(5)
                ->get(),
            'certificates' => Certificate::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(5)
                ->get(),
        ];

        return response()->json([
            'query' => $query,
            'results' => $results,
            'total' => array_sum(array_map('count', $results)),
        ]);
    }

    /**
     * Export dashboard data.
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'pdf');
        $data = $this->prepareExportData();

        // You can implement different export formats here
        // For now, return JSON response
        return response()->json([
            'data' => $data,
            'exported_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Prepare data for export.
     */
    private function prepareExportData()
    {
        return [
            'statistics' => [
                'total_projects' => Project::count(),
                'total_tenders' => Tender::count(),
                'total_requests' => UserRequest::count(),
                'total_certificates' => Certificate::count(),
            ],
            'generated_at' => now()->toDateTimeString(),
            'user' => auth()->user()->only(['name', 'email']),
        ];
    }
}
