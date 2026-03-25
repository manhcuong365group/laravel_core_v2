<?php

namespace App\Livewire\Backend\Orders;

use App\Actions\Order\UpdateOrderStatusAction;
use App\Models\Order;
use Livewire\Component;

class ShowPage extends Component
{
    public Order $order;
    public string $status = '';
    public string $statusNote = '';
    public bool $showStatusModal = false;

    public function mount(Order $order): void
    {
        $this->authorize('view', $order);
        $this->order = $order->load(['items.product', 'statusHistories.changer']);
        $this->status = $order->status;
    }

    public function updateStatus(UpdateOrderStatusAction $action): void
    {
        $this->authorize('update', $this->order);
        
        $success = $action->execute($this->order, $this->status, $this->statusNote);
        
        if ($success) {
            $this->dispatch('toast', message: 'Cập nhật trạng thái đơn hàng thành công.', type: 'success');
            $this->order->refresh()->load(['items.product', 'statusHistories.changer']);
        } else {
            $this->dispatch('toast', message: 'Trạng thái không thay đổi.', type: 'info');
        }
        
        $this->showStatusModal = false;
        $this->statusNote = '';
    }

    public function render()
    {
        return view('livewire.backend.orders.show-page', [
            'statuses' => Order::statusOptions(),
            'title' => 'Chi tiết đơn: ' . $this->order->code,
        ])->layout('backend.layouts.app', [
            'title' => 'Chi tiết đơn: ' . $this->order->code,
        ]);
    }
}

