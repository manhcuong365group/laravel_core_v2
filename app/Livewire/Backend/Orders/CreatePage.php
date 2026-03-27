<?php

namespace App\Livewire\Backend\Orders;

use App\Actions\Order\CreateOrderAction;
use App\Data\OrderData;
use App\Models\Order;
use App\Traits\WithOrderForms;
use Livewire\Component;

class CreatePage extends Component
{
    use WithOrderForms;

    public function mount(): void
    {
        $this->authorize('create', Order::class);
        $this->status = Order::STATUS_NEW;
    }

    protected function rules(): array
    {
        return $this->orderRules();
    }

    public function save(CreateOrderAction $action)
    {
        $validated = $this->validate();

        try {
            $data = OrderData::fromArray($validated);
            $order = $action->execute($data);

            session()->flash('success', 'Đơn hàng đã được tạo thành công.');
            return $this->redirect(route('backend.orders.show', $order), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo đơn hàng!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.orders.create-page', [
            'products' => $this->getAvailableProducts(),
            'statuses' => $this->getStatusOptions(),
            'title' => 'Tạo đơn hàng',
        ])->layout('backend.layouts.app', [
            'title' => 'Tạo đơn hàng',
        ]);
    }
}
