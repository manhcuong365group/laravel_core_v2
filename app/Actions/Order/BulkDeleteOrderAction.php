<?php

namespace App\Actions\Order;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class BulkDeleteOrderAction
{
    public function execute(array $ids): int
    {
        $orders = Order::query()->whereIn('id', $ids)->get();
        $count = $orders->count();
        $action = new DeleteOrderAction();

        DB::transaction(function () use ($orders, $action) {
            foreach ($orders as $order) {
                $action->execute($order);
            }
        });

        return $count;
    }
}
