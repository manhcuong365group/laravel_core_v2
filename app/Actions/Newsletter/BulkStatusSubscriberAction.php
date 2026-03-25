<?php

namespace App\Actions\Newsletter;

use App\Models\Subscriber;

class BulkStatusSubscriberAction
{
    /**
     * @param array<int> $ids
     * @param string $status
     */
    public function execute(array $ids, string $status): int
    {
        return Subscriber::query()->whereIn('id', $ids)->update(['status' => $status]);
    }
}
