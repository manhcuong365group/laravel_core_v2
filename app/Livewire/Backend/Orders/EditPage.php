<?php

namespace App\Livewire\Backend\Orders;

use App\Actions\Order\UpdateOrderAction;
use App\Data\OrderData;
use App\Models\Order;
use App\Traits\WithOrderForms;
use Livewire\Component;

class EditPage extends Component
{
    use WithOrderForms;

    public int $orderId;

    public function mount(Order $order): void
    {
        $this->authorize('update', $order);
        
        $this->orderId = $order->id;
        $this->customer_name = $order->customer_name;
        $this->customer_phone = $order->customer_phone;
        $this->customer_email = $order->customer_email;
        $this->customer_address = $order->customer_address;
        $this->shipping_fee = (float) $order->shipping_fee;
        $this->status = $order->status;
        $this->notes = $order->notes;
        $this->admin_notes = $order->admin_notes;
        
        $this->items = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name_snapshot' => $item->product_name_snapshot,
                'sku_snapshot' => $item->sku_snapshot,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
                'line_total' => (float) $item->total,
            ];
        })->toArray();

        $this->calculateTotals();
    }

    protected function rules(): array
    {
        return $this->orderRules();
    }

    public function save(UpdateOrderAction $action)
    {
        $order = Order::findOrFail($this->orderId);
        $this->authorize('update', $order);

        $validated = $this->validate();

        try {
            $data = OrderData::fromArray($validated);
            $action->execute($order, $data);

            session()->flash('success', 'Đơn hàng đã được cập nhật thành công.');
            return $this->redirect(route('backend.orders.show', $order), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật đơn hàng!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.orders.edit-page', [
            'products' => $this->getAvailableProducts(),
            'statuses' => $this->getStatusOptions(),
            'title' => 'Sửa đơn hàng',
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa đơn hàng',
        ]);
    }
}
