<?php

namespace App\Actions\Product;

use App\Models\Product;

class BulkDeleteProductAction
{
    public function execute(array $ids): int
    {
        return Product::query()
            ->whereIn('id', $ids)
            ->delete();
    }
}
