<?php

namespace App\Livewire\Backend\Dashboard;

use App\Models\Contact;
use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Bảng điều khiển | Command Center')]
class IndexPage extends Component
{
    public array $stats = [];
    public $recentOrders = [];

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        // 1. New Orders & Growth
        // Simplified stats computation
        $newOrders = Order::where('status', Order::STATUS_NEW)->count(); 
        $lastMonthOrders = Order::where('status', Order::STATUS_NEW)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();
        $orderGrowth = $lastMonthOrders > 0 ? (($newOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 0;

        // 2. Revenue & Growth
        $revenueThisMonth = Order::query()->where('status', Order::STATUS_COMPLETED)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total');
        $revenueLastMonth = Order::query()->where('status', Order::STATUS_COMPLETED)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('total');
        $revenueGrowth = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        // 3. New Customers & Growth
        $newCustomers = \App\Models\User::query()->where('created_at', '>=', now()->startOfMonth())->count();
        $lastMonthCustomers = \App\Models\User::query()
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();
        $customerGrowth = $lastMonthCustomers > 0 ? (($newCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 : 0;

        // 4. Other stats
        $newContacts = Contact::query()->where('status', 'new')->count();
        $popularProducts = \App\Models\Product::query()->orderBy('view_count', 'desc')->take(4)->get();
        $this->recentOrders = Order::query()->latest()->take(5)->get();

        $this->stats = [
            'newOrders' => $newOrders,
            'orderGrowth' => round($orderGrowth, 1),
            'newContacts' => $newContacts,
            'newCustomers' => $newCustomers,
            'customerGrowth' => round($customerGrowth, 1),
            'revenueThisMonth' => $revenueThisMonth,
            'revenueGrowth' => round($revenueGrowth, 1),
            'popularProducts' => $popularProducts,
            'generatedAt' => now()->format('H:i:s'),
        ];
    }

    public function render()
    {
        return view('livewire.backend.dashboard.index-page', [
            'title' => 'Bảng điều khiển',
            'recentOrders' => $this->recentOrders,
        ])->layout('backend.layouts.app', [
            'title' => 'Bảng điều khiển',
        ]);
    }
}

