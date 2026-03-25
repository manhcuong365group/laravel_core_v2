<?php

namespace App\Actions\Brand;

use App\Models\Brand;

class BulkDeleteBrandAction
{
    /**
     * @param array<int> $ids
     */
    public function execute(array $ids): int
    {
        return Brand::query()->whereIn('id', $ids)->delete();
    }
}
