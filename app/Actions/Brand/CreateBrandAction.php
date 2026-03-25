<?php

namespace App\Actions\Brand;

use App\Data\BrandData;
use App\Models\Brand;
use App\Services\Media\MediaService;

class CreateBrandAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(BrandData $data): Brand
    {
        $brand = Brand::create($data->toArray());

        $this->mediaService->uploadSingle($brand, $data->logo, 'logo');

        return $brand;
    }
}
