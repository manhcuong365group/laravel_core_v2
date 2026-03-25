<?php

namespace App\Actions\Brand;

use App\Models\Brand;

class DeleteBrandAction
{
    public function execute(Brand $brand): ?bool
    {
        return $brand->delete();
    }
}
