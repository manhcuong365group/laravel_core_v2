<?php

namespace App\Actions\Category;

use App\Models\Category;

class BulkStatusCategoryAction
{
    /**
     * @param array<int> $ids
     * @param bool $isActive
     */
    public function execute(array $ids, bool $isActive): int
    {
        return Category::query()->whereIn('id', $ids)->update(['is_active' => $isActive]);
    }
}
