<?php

namespace App\Actions\Brand;

use App\Data\BrandData;
use App\Models\Brand;
use App\Services\Media\MediaService;

class UpdateBrandAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(Brand $brand, BrandData $data): Brand
    {
        $brand->update($data->toArray());

        $this->mediaService->uploadSingle($brand, $data->logo, 'logo');

        return $brand;
    }
}
