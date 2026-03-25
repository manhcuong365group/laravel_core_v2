<?php

namespace App\Actions\Order;

use App\Data\OrderData;
use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CreateOrderAction
{
    public function execute(OrderData $data): Order
    {
        return DB::transaction(function () use ($data) {
            [$subtotal, $items] = $this->normalizeItems($data->items);
            
            $order = Order::create([
                'code' => $this->generateOrderCode(),
                'customer_name' => $data->customer_name,
                'customer_phone' => $data->customer_phone,
                'customer_email' => $data->customer_email,
                'customer_address' => $data->customer_address,
                'subtotal' => $subtotal,
                'shipping_fee' => $data->shipping_fee,
                'total' => $subtotal + $data->shipping_fee,
                'status' => $data->status,
                'notes' => $data->notes,
                'admin_notes' => $data->admin_notes,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $order->items()->createMany($items);

            $order->statusHistories()->create([
                'from_status' => null,
                'to_status' => $order->status,
                'note' => 'Tạo đơn hàng',
                'changed_by' => auth()->id(),
            ]);

            ActivityLog::log('orders.create', $order, [
                'code' => $order->code,
                'status' => $order->status,
                'total' => $order->total,
            ]);

            return $order;
        });
    }

    /**
     * @param array<int, array<string, mixed>> $rawItems
     * @return array{0: float, 1: array<int, array<string, mixed>>}
     */
    protected function normalizeItems(array $rawItems): array
    {
        $subtotal = 0.0;
        $items = [];

        foreach ($rawItems as $item) {
            $price = (float) ($item['price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 1);
            $lineTotal = $price * $quantity;
            $subtotal += $lineTotal;

            $items[] = [
                'product_id' => $item['product_id'] ?? null,
                'product_name_snapshot' => $item['product_name_snapshot'] ?? ($item['name'] ?? ''),
                'sku_snapshot' => $item['sku_snapshot'] ?? ($item['sku'] ?? null),
                'price' => $price,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
        }

        return [$subtotal, $items];
    }

    protected function generateOrderCode(): string
    {
        do {
            $code = 'ORD-' . now()->format('Ymd') . '-' . mt_rand(1000, 9999);
        } while (Order::where('code', $code)->exists());

        return $code;
    }
}
