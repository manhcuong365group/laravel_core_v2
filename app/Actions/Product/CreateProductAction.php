<?php

namespace App\Actions\Product;

use App\Data\ProductData;
use App\Events\ProductCreated;
use App\Models\Product;
use App\Services\Media\MediaService;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(ProductData $data): Product
    {
        $product = DB::transaction(function () use ($data) {
            $product = Product::create($data->toArray());

            $this->mediaService->uploadSingle($product, $data->featured_image, 'featured_image');
            $this->mediaService->uploadMultiple($product, $data->gallery, 'gallery');

            return $product;
        });

        event(new ProductCreated($product));

        return $product;
    }
}
