<?php

namespace App\Livewire\Backend\Dashboard;

use App\Models\Product;
use App\Models\Article;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Carbon;

class RevenueChart extends Component
{
    public array $monthlyData = [];
    public array $labels = [];
    public string $period = 'year';

    public function mount(): void
    {
        $this->loadChartData();
    }

    public function loadChartData(): void
    {
        $months = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push([
                'label' => $date->format('M'),
                'products' => Product::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'articles' => Article::where('type', 'post')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ]);
        }

        $this->labels = $months->pluck('label')->toArray();
        $this->monthlyData = [
            'products' => $months->pluck('products')->toArray(),
            'articles' => $months->pluck('articles')->toArray(),
            'users' => $months->pluck('users')->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.backend.dashboard.revenue-chart');
    }
}


