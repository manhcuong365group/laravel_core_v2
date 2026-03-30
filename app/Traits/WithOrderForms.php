<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

trait WithOrderForms
{
    public string $customer_name = '';
    public string $customer_phone = '';
    public ?string $customer_email = '';
    public ?string $customer_address = '';
    public float $shipping_fee = 0;
    public string $status = 'pending';
    public ?string $notes = '';
    public ?string $admin_notes = '';
    
    public array $items = [];
    public float $subtotal = 0;
    public float $total = 0;

    /**
     * Common rules for Orders.
     */
    protected function orderRules(): array
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
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.product_name_snapshot' => 'required|string',
            'items.*.sku_snapshot' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Add a new empty item to the order.
     */
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

    /**
     * Remove an item from the order by index.
     */
    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    /**
     * Update product details in the items array.
     */
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

    /**
     * Update quantity of an item.
     */
    public function updateQuantity(int $index, int $quantity): void
    {
        $this->items[$index]['quantity'] = max(1, $quantity);
        $this->items[$index]['line_total'] = $this->items[$index]['price'] * $this->items[$index]['quantity'];
        $this->calculateTotals();
    }

    /**
     * Calculate subtotal and total for the order.
     */
    public function calculateTotals(): void
    {
        $this->subtotal = 0;
        foreach ($this->items as $item) {
            $this->subtotal += ($item['line_total'] ?? 0);
        }
        $this->total = $this->subtotal + (float) $this->shipping_fee;
    }

    /**
     * Hook to recalculate totals when shipping fee is updated.
     */
    public function updatedShippingFee(): void
    {
        $this->calculateTotals();
    }

    /**
     * Get available products for selection.
     */
    public function getAvailableProducts(): Collection
    {
        return Product::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'sku', 'price']);
    }

    /**
     * Get order status options.
     */
    public function getStatusOptions(): array
    {
        return Order::statusOptions();
    }
}
