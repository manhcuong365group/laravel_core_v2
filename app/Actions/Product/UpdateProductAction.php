<?php

namespace App\Actions\Product;

use App\Data\ProductData;
use App\Events\ProductUpdated;
use App\Models\Product;
use App\Services\Media\MediaService;
use Illuminate\Support\Facades\DB;

class UpdateProductAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(Product $product, ProductData $data): Product
    {
        DB::transaction(function () use ($product, $data) {
            $product->update($data->toArray());

            $this->mediaService->uploadSingle($product, $data->featured_image, 'featured_image');
            $this->mediaService->uploadMultiple($product, $data->gallery, 'gallery');
        });

        event(new ProductUpdated($product));

        return $product;
    }
}

