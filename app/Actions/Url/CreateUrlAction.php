<?php

namespace App\Actions\Url;

use App\Data\UrlData;
use App\Models\Url;
use Illuminate\Support\Facades\DB;

class CreateUrlAction
{
    public function execute(UrlData $data): Url
    {
        return DB::transaction(fn () => Url::create($data->toArray()));
    }
}
