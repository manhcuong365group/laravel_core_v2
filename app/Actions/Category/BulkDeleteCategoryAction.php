<?php

namespace App\Actions\Category;

use App\Models\Category;

class BulkDeleteCategoryAction
{
    /**
     * @param array<int> $ids
     */
    public function execute(array $ids): int
    {
        return Category::query()->whereIn('id', $ids)->delete();
    }
}
