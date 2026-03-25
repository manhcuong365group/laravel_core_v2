<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'type' => $this->type,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'allow_comments' => $this->allow_comments,
            'view_count' => $this->view_count,
            'order' => $this->order,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            
            'featured_image' => [
                'original' => $this->getFirstMediaUrl('featured_image'),
                'thumb' => $this->getFirstMediaUrl('featured_image', 'thumb'),
                'medium' => $this->getFirstMediaUrl('featured_image', 'medium'),
            ],
            
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => new UserResource($this->whenLoaded('author')),
            
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
                'canonical_url' => $this->canonical_url,
            ],
        ];
    }
}
