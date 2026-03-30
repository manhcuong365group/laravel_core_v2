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
            $productData = $data->toArray();
            
            // If has variants, reset main product stock
            if ($data->has_variants) {
                $productData['stock_quantity'] = 0;
            }

            $product->update($productData);

            // Handle Variants Update
            if ($data->has_variants && !empty($data->variants)) {
                $existingVariants = $product->variants()->get()->keyBy('sku');
                $newVariantSkus = collect($data->variants)->pluck('sku')->toArray();

                // 1. Delete variants that are no longer in the updated data
                $product->variants()->whereNotIn('sku', $newVariantSkus)->delete();

                foreach ($data->variants as $variant) {
                    if (!($variant['is_active'] ?? true)) continue;

                    // 2. Update or Create by SKU
                    $price = (float) str_replace(['.', ','], '', $variant['price']);
                    $stock = (int) $variant['stock'];

                    $variantModel = $product->variants()->updateOrCreate(
                        ['sku' => $variant['sku']],
                        [
                            'price' => $price,
                            'stock_quantity' => $stock,
                            'is_active' => true,
                        ]
                    );

                    // 3. Sync attribute values for the variant
                    if (!empty($variant['attribute_value_ids'])) {
                        $variantModel->attributeValues()->sync($variant['attribute_value_ids']);
                    }
                }
            } else {
                // If toggled off or no variants provided, delete all existing variants
                $product->variants()->each->delete();
            }

            $this->mediaService->uploadSingle($product, $data->featured_image, 'featured_image');
            $this->mediaService->uploadMultiple($product, $data->gallery, 'gallery');
        });

        event(new ProductUpdated($product));

        return $product;
    }
}

