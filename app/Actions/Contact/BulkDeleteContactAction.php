<?php

namespace App\Actions\Contact;

use App\Models\Contact;

class BulkDeleteContactAction
{
    /**
     * @param array<int> $ids
     */
    public function execute(array $ids): int
    {
        return Contact::query()->whereIn('id', $ids)->delete();
    }
}
