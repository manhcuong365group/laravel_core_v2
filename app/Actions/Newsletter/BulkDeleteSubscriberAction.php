<?php

namespace App\Actions\Newsletter;

use App\Models\Subscriber;

class BulkDeleteSubscriberAction
{
    /**
     * @param array<int> $ids
     */
    public function execute(array $ids): int
    {
        return Subscriber::query()->whereIn('id', $ids)->delete();
    }
}
