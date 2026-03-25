<?php

namespace App\Livewire\Backend\Dashboard;

use App\Models\User;
use App\Models\Product;
use App\Models\Article;
use App\Models\Category;
use Livewire\Component;

class StatsOverview extends Component
{
    public int $totalUsers = 0;
    public int $totalProducts = 0;
    public int $totalArticles = 0;
    public int $totalCategories = 0;

    public float $userGrowth = 0;
    public float $productGrowth = 0;
    public float $articleGrowth = 0;

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->totalUsers = User::count();
        $this->totalProducts = Product::count();
        $this->totalArticles = Article::where('type', 'post')->count();
        $this->totalCategories = Category::count();

        // Growth calculations (current month vs last month)
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $usersThisMonth = User::where('created_at', '>=', $startOfMonth)->count();
        $usersLastMonth = User::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $this->userGrowth = $usersLastMonth > 0 ? round(($usersThisMonth - $usersLastMonth) / $usersLastMonth * 100, 1) : 0;

        $productsThisMonth = Product::where('created_at', '>=', $startOfMonth)->count();
        $productsLastMonth = Product::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $this->productGrowth = $productsLastMonth > 0 ? round(($productsThisMonth - $productsLastMonth) / $productsLastMonth * 100, 1) : 0;

        $articlesThisMonth = Article::where('type', 'post')->where('created_at', '>=', $startOfMonth)->count();
        $articlesLastMonth = Article::where('type', 'post')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $this->articleGrowth = $articlesLastMonth > 0 ? round(($articlesThisMonth - $articlesLastMonth) / $articlesLastMonth * 100, 1) : 0;
    }

    public function render()
    {
        return view('livewire.backend.dashboard.stats-overview');
    }
}


