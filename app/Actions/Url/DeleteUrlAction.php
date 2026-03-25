<?php

namespace App\Actions\Url;

use App\Models\Url;
use Illuminate\Support\Facades\DB;

class DeleteUrlAction
{
    public function execute(Url $url): bool
    {
        return DB::transaction(fn () => $url->delete());
    }
}
