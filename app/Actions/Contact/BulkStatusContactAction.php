<?php

namespace App\Actions\Contact;

use App\Models\Contact;

class BulkStatusContactAction
{
    /**
     * @param array<int> $ids
     * @param string $status
     */
    public function execute(array $ids, string $status): int
    {
        return Contact::query()->whereIn('id', $ids)->update(['status' => $status]);
    }
}
