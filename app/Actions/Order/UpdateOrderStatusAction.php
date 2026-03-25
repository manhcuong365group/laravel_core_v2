<?php

namespace App\Actions\Order;

use App\Models\ActivityLog;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class UpdateOrderStatusAction
{
    public function execute(Order $order, string $status, ?string $note = null): bool
    {
        $fromStatus = $order->status;
        $toStatus = $status;

        if ($fromStatus === $toStatus) {
            return false;
        }

        return DB::transaction(function () use ($order, $fromStatus, $toStatus, $note) {
            $order->update([
                'status' => $toStatus,
                'updated_by' => auth()->id(),
            ]);

            $order->statusHistories()->create([
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'note' => $note,
                'changed_by' => auth()->id(),
            ]);

            ActivityLog::log('orders.status', $order, [
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'note' => $note,
            ]);

            return true;
        });
    }
}
