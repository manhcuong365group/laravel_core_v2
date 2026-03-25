<?php

namespace App\Actions\Article;

use App\Models\Article;

class BulkStatusArticleAction
{
    private const ALLOWED_STATUSES = ['draft', 'published', 'scheduled'];

    public function execute(array $ids, string $status): int
    {
        if (!in_array($status, self::ALLOWED_STATUSES, true) || empty($ids)) {
            return 0;
        }

        return Article::query()
            ->whereIn('id', $ids)
            ->update(['status' => $status]);
    }
}
