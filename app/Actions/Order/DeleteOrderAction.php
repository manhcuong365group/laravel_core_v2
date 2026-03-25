<?php

namespace App\Actions\Order;

use App\Models\ActivityLog;
use App\Models\Order;

class DeleteOrderAction
{
    public function execute(Order $order): bool
    {
        $code = $order->code;
        $result = $order->delete();

        if ($result) {
            ActivityLog::log('orders.delete', $order, [
                'code' => $code,
            ]);
        }

        return $result;
    }
}
