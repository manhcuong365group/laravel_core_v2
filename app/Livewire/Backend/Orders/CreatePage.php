<?php

namespace App\Livewire\Backend\Orders;

use App\Actions\Order\CreateOrderAction;
use App\Data\OrderData;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class CreatePage extends Component
{
    public string $customer_name = '';
    public string $customer_phone = '';
    public ?string $customer_email = '';
    public ?string $customer_address = '';
    public float $shipping_fee = 0;
    public string $status = Order::STATUS_NEW;
    public ?string $notes = '';
    public ?string $admin_notes = '';
    
    public array $items = [];
    public float $subtotal = 0;
    public float $total = 0;

    public function mount(): void
    {
        $this->authorize('create', Order::class);
    }

    public function addItem(): void
    {
        $this->items[] = [
            'product_id' => null,
            'product_name_snapshot' => '',
            'sku_snapshot' => '',
            'price' => 0,
            'quantity' => 1,
            'line_total' => 0,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updateProduct(int $index, int $productId): void
    {
        $product = Product::find($productId);
        if ($product) {
            $this->items[$index]['product_id'] = $product->id;
            $this->items[$index]['product_name_snapshot'] = $product->name;
            $this->items[$index]['sku_snapshot'] = $product->sku;
            $this->items[$index]['price'] = $product->price;
            $this->items[$index]['line_total'] = $this->items[$index]['price'] * $this->items[$index]['quantity'];
        }
        $this->calculateTotals();
    }

    public function updateQuantity(int $index, int $quantity): void
    {
        $this->items[$index]['quantity'] = $quantity;
        $this->items[$index]['line_total'] = $this->items[$index]['price'] * $this->items[$index]['quantity'];
        $this->calculateTotals();
    }

    public function calculateTotals(): void
    {
        $this->subtotal = 0;
        foreach ($this->items as $item) {
            $this->subtotal += $item['line_total'];
        }
        $this->total = $this->subtotal + (float) $this->shipping_fee;
    }

    public function updatedShippingFee(): void
    {
        $this->calculateTotals();
    }

    protected function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string|max:500',
            'shipping_fee' => 'required|numeric|min:0',
            'status' => 'required|string|in:' . implode(',', array_keys(Order::statusOptions())),
            'notes' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_name_snapshot' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function save(CreateOrderAction $action)
    {
        $this->validate();

        $data = OrderData::fromArray([
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'customer_address' => $this->customer_address,
            'shipping_fee' => $this->shipping_fee,
            'status' => $this->status,
            'notes' => $this->notes,
            'admin_notes' => $this->admin_notes,
            'items' => $this->items,
        ]);

        $order = $action->execute($data);

        session()->flash('success', 'Đơn hàng đã được tạo thành công.');
        return redirect()->route('backend.orders.show', $order);
    }

    public function render()
    {
        return view('livewire.backend.orders.create-page', [
            'products' => Product::query()->orderBy('name')->get(['id', 'name', 'sku', 'price']),
            'statuses' => Order::statusOptions(),
            'title' => 'Tạo đơn hàng',
        ])->layout('backend.layouts.app', [
            'title' => 'Tạo đơn hàng',
        ]);
    }
}


