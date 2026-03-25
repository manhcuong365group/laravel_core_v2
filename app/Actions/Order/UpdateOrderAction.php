<?php

namespace App\Actions\Order;

use App\Data\OrderData;
use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class UpdateOrderAction
{
    public function execute(Order $order, OrderData $data): Order
    {
        return DB::transaction(function () use ($order, $data) {
            [$subtotal, $items] = $this->normalizeItems($data->items);
            $oldStatus = $order->status;

            $order->update([
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
                'updated_by' => auth()->id(),
            ]);

            $order->items()->delete();
            $order->items()->createMany($items);

            if ($oldStatus !== $order->status) {
                $order->statusHistories()->create([
                    'from_status' => $oldStatus,
                    'to_status' => $order->status,
                    'note' => 'Cập nhật trạng thái từ form sửa đơn',
                    'changed_by' => auth()->id(),
                ]);
            }

            ActivityLog::log('orders.update', $order, [
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
}
