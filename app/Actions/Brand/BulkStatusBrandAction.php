<?php

namespace App\Actions\Brand;

use App\Models\Brand;

class BulkStatusBrandAction
{
    /**
     * @param array<int> $ids
     * @param bool $isActive
     */
    public function execute(array $ids, bool $isActive): int
    {
        return Brand::query()->whereIn('id', $ids)->update(['is_active' => $isActive]);
    }
}
