<?php

namespace App\Actions\Article;

use App\Models\Article;

class BulkDeleteArticleAction
{
    public function execute(array $ids): int
    {
        if (empty($ids)) {
            return 0;
        }

        return Article::query()
            ->whereIn('id', $ids)
            ->delete();
    }
}
