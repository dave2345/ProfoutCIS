<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Project;
use App\Models\Tender;
use App\Models\Request as UserRequest;
use Illuminate\Support\Carbon;

class PerformanceChart extends Component
{
    public $period = 'monthly'; // monthly, quarterly, yearly
    public $data = [];
    public $chartType = 'bar'; // bar, line, area

    protected $listeners = ['refreshChart' => 'loadData'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->data = $this->getChartData();
    }

    public function updatePeriod($period)
    {
        $this->period = $period;
        $this->loadData();
    }

    public function updateChartType($type)
    {
        $this->chartType = $type;
    }

    private function getChartData()
    {
        $data = [];

        if ($this->period === 'monthly') {
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthName = $month->format('M');

                $data[] = [
                    'month' => $monthName,
                    'projects' => Project::whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->count(),
                    'tenders' => Tender::whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->count(),
                    'requests' => UserRequest::whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->count(),
                    'revenue' => rand(10000, 50000), // Replace with actual revenue data
                ];
            }
        } elseif ($this->period === 'quarterly') {
            // Quarterly data implementation
            for ($i = 3; $i >= 0; $i--) {
                $quarterStart = now()->subQuarters($i)->startOfQuarter();
                $quarterName = 'Q' . ceil($quarterStart->month / 3) . ' ' . $quarterStart->format('y');

                $data[] = [
                    'quarter' => $quarterName,
                    'projects' => Project::whereBetween('created_at', [
                        $quarterStart,
                        $quarterStart->copy()->endOfQuarter()
                    ])->count(),
                    'tenders' => Tender::whereBetween('created_at', [
                        $quarterStart,
                        $quarterStart->copy()->endOfQuarter()
                    ])->count(),
                    'requests' => UserRequest::whereBetween('created_at', [
                        $quarterStart,
                        $quarterStart->copy()->endOfQuarter()
                    ])->count(),
                    'revenue' => rand(30000, 150000),
                ];
            }
        } else { // yearly
            for ($i = 4; $i >= 0; $i--) {
                $year = now()->subYears($i)->year;
                $yearName = $year;

                $data[] = [
                    'year' => $yearName,
                    'projects' => Project::whereYear('created_at', $year)->count(),
                    'tenders' => Tender::whereYear('created_at', $year)->count(),
                    'requests' => UserRequest::whereYear('created_at', $year)->count(),
                    'revenue' => rand(100000, 500000),
                ];
            }
        }

        return $data;
    }

    public function getMaxValue()
    {
        if (empty($this->data)) return 100;

        $maxValues = [];
        foreach ($this->data as $item) {
            $maxValues[] = max($item['projects'], $item['tenders'], $item['requests']);
        }

        return max($maxValues) ?: 100;
    }

    public function render()
    {
        return view('livewire.dashboard.performance-chart', [
            'maxValue' => $this->getMaxValue(),
        ]);
    }
}
