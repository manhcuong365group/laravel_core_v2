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
            $productData = $data->toArray();
            
            // If has variants, clear main product stock (variants handle it)
            if ($data->has_variants) {
                $productData['stock_quantity'] = 0;
            }

            $product = Product::create($productData);

            if ($data->has_variants && !empty($data->variants)) {
                foreach ($data->variants as $variant) {
                    if (!($variant['is_active'] ?? true)) continue;

                    $newVariant = $product->variants()->create([
                        'sku' => $variant['sku'],
                        'price' => (float) str_replace(['.', ','], '', $variant['price']),
                        'stock_quantity' => (int) $variant['stock'],
                        'is_active' => true,
                    ]);

                    if (!empty($variant['attribute_value_ids'])) {
                        $newVariant->attributeValues()->sync($variant['attribute_value_ids']);
                    }
                }
            }

            $this->mediaService->uploadSingle($product, $data->featured_image, 'featured_image');
            $this->mediaService->uploadMultiple($product, $data->gallery, 'gallery');

            return $product;
        });

        event(new ProductCreated($product));

        return $product;
    }
}
