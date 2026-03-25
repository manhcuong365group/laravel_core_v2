<?php

namespace App\Actions\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class BulkStatusOrderAction
{
    public function execute(array $ids, string $status, ?string $note = null): int
    {
        $orders = Order::query()->whereIn('id', $ids)->get();
        $updatedCount = 0;
        $action = new UpdateOrderStatusAction();

        DB::transaction(function () use ($orders, $status, $note, &$updatedCount, $action) {
            foreach ($orders as $order) {
                if ($action->execute($order, $status, $note)) {
                    $updatedCount++;
                }
            }
        });

        return $updatedCount;
    }
}
