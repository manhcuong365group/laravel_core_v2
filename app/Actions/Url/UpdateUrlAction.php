<?php

namespace App\Actions\Url;

use App\Data\UrlData;
use App\Models\Url;
use Illuminate\Support\Facades\DB;

class UpdateUrlAction
{
    public function execute(Url $url, UrlData $data): Url
    {
        return DB::transaction(function () use ($url, $data) {
            $url->update($data->toArray());
            return $url;
        });
    }
}
