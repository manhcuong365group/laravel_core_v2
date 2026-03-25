<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'content' => $this->content,
            'price' => (float) $this->price,
            'sale_price' => (float) $this->sale_price,
            'stock_quantity' => $this->stock_quantity,
            'stock_status' => $this->stock_status,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'order' => $this->order,
            'view_count' => $this->view_count,
            
            'featured_image' => [
                'original' => $this->getFirstMediaUrl('featured_image'),
                'thumb' => $this->getFirstMediaUrl('featured_image', 'thumb'),
                'medium' => $this->getFirstMediaUrl('featured_image', 'medium'),
            ],
            
            'gallery' => $this->getMedia('gallery')->map(fn($media) => [
                'original' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ]),
            
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
            ],
            
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
