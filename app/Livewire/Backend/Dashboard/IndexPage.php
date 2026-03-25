<?php

namespace App\Livewire\Backend\Dashboard;

use App\Models\Contact;
use App\Models\Order;
use Livewire\Component;

class IndexPage extends Component
{
    public array $stats = [];

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $newOrders = Order::query()->where('status', Order::STATUS_NEW)->count();
        $newContacts = Contact::query()->where('status', 'new')->count();

        $totalOrders = Order::query()->count();
        $processedOrders = Order::query()->where('status', Order::STATUS_COMPLETED)->count();
        $orderProcessingRate = $totalOrders > 0 ? round(($processedOrders / $totalOrders) * 100, 1) : 0.0;

        $totalContacts = Contact::query()->count();
        $processedContacts = Contact::query()->whereIn('status', ['read', 'replied'])->count();
        $contactProcessingRate = $totalContacts > 0 ? round(($processedContacts / $totalContacts) * 100, 1) : 0.0;

        $this->stats = [
            'new_orders' => $newOrders,
            'new_contacts' => $newContacts,
            'order_processing_rate' => $orderProcessingRate,
            'contact_processing_rate' => $contactProcessingRate,
            'generated_at' => now()->format('H:i:s'),
        ];
    }

    public function render()
    {
        return view('livewire.backend.dashboard.index-page', [
            'title' => 'Bảng điều khiển',
        ])->layout('backend.layouts.app', [
            'title' => 'Bảng điều khiển',
        ]);
    }
}

