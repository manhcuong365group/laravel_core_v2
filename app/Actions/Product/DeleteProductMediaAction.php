<?php

namespace App\Actions\Product;

use App\Models\Product;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeleteProductMediaAction
{
    /**
     * Execute the action.
     * 
     * @param Product $product
     * @param int $mediaId
     * @return bool
     */
    public function execute(Product $product, int $mediaId): bool
    {
        $media = $product->media()->find($mediaId);

        if (!$media) {
            return false;
        }

        return (bool) $media->delete();
    }
}
