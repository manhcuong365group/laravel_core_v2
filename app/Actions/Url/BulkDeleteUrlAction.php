<?php

namespace App\Actions\Url;

use App\Models\Url;
use Illuminate\Support\Facades\DB;

class BulkDeleteUrlAction
{
    /**
     * @param int[] $ids
     */
    public function execute(array $ids): bool
    {
        return DB::transaction(fn () => Url::whereIn('id', $ids)->delete());
    }
}
