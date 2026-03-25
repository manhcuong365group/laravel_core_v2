<?php

namespace App\Actions\Product;

use App\Models\Product;

class BulkStatusProductAction
{
    public function execute(array $ids, bool $isActive): int
    {
        return Product::query()
            ->whereIn('id', $ids)
            ->update(['is_active' => $isActive]);
    }
}
