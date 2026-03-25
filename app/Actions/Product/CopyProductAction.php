<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CopyProductAction
{
    public function execute(Product $product): Product
    {
        try {
            [$clone, $source] = DB::transaction(function () use ($product) {
                $source = $product->fresh(['tags', 'attributeValues']);

                $attributes = $source->only([
                    'name',
                    'short_description',
                    'content',
                    'price',
                    'sale_price',
                    'stock_quantity',
                    'stock_status',
                    'category_id',
                    'brand_id',
                    'is_active',
                    'is_featured',
                    'order',
                    'meta_title',
                    'meta_description',
                    'meta_keywords',
                    'featured_image',
                ]);

                $attributes['slug'] = $this->generateUniqueSlug($source->name);
                $attributes['sku'] = $this->generateUniqueSku($source->sku);
                $attributes['view_count'] = 0;

                $clone = Product::create($attributes);

                if ($source->tags->count() > 0) {
                    $clone->tags()->sync($source->tags->pluck('id')->all());
                }

                $pivotData = $source->attributeValues
                    ->mapWithKeys(fn($value) => [
                        $value->id => ['price_adjustment' => $value->pivot->price_adjustment],
                    ])
                    ->all();

                if (!empty($pivotData)) {
                    $clone->attributeValues()->sync($pivotData);
                }

                return [$clone, $source];
            });

            try {
                foreach ($source->getMedia('featured_image') as $media) {
                    $media->copy($clone, 'featured_image');
                }

                foreach ($source->getMedia('gallery') as $media) {
                    $media->copy($clone, 'gallery');
                }
            } catch (Throwable $mediaException) {
                Log::warning('Nhân bản sản phẩm thành công nhưng copy media thất bại', [
                    'source_product_id' => $product->id,
                    'copied_product_id' => $clone->id,
                    'error' => $mediaException->getMessage(),
                ]);
            }

            return $clone;
        } catch (Throwable $e) {
            Log::error('Không thể nhân bản sản phẩm', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function generateUniqueSku(?string $baseSku): string
    {
        $base = trim((string) $baseSku) !== '' ? (string) $baseSku : 'SP-' . Str::upper(Str::random(6));
        $candidate = $base . '-COPY';
        $counter = 1;

        while (Product::withTrashed()->where('sku', $candidate)->exists()) {
            $candidate = $base . '-COPY-' . $counter;
            $counter++;
        }

        return $candidate;
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? $base : 'san-pham';
        $candidate = $base . '-copy';
        $counter = 1;

        while (Product::withTrashed()->where('slug', $candidate)->exists()) {
            $candidate = $base . '-copy-' . $counter;
            $counter++;
        }

        return $candidate;
    }
}
