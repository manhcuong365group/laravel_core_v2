<?php

namespace App\Livewire\Backend\Dashboard;

use App\Models\Product;
use App\Models\Article;
use App\Models\User;
use Livewire\Component;

class RecentActivity extends Component
{
    public $recentProducts;
    public $recentArticles;
    public $recentUsers;

    public function mount(): void
    {
        $this->loadActivity();
    }

    public function loadActivity(): void
    {
        $this->recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        $this->recentArticles = Article::with('category')
            ->where('type', 'post')
            ->latest()
            ->take(5)
            ->get();

        $this->recentUsers = User::latest()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.backend.dashboard.recent-activity');
    }
}


