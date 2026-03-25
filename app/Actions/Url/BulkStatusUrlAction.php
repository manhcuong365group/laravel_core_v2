<?php

namespace App\Actions\Url;

use App\Models\Url;
use Illuminate\Support\Facades\DB;

class BulkStatusUrlAction
{
    /**
     * @param int[] $ids
     */
    public function execute(array $ids, bool $status): bool
    {
        return DB::transaction(fn () => Url::whereIn('id', $ids)->update(['is_active' => $status]));
    }
}
