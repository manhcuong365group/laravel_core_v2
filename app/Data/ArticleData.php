<?php

namespace App\Data;

use Illuminate\Http\Request;

class ArticleData
{
    public function __construct(
        public string $title,
        public ?string $slug,
        public ?string $excerpt,
        public ?string $content,
        public ?int $category_id,
        public string $type,
        public string $status,
        public ?string $published_at,
        public bool $is_featured,
        public bool $allow_comments,
        public ?int $order,
        public ?string $meta_title,
        public ?string $meta_description,
        public ?string $meta_keywords,
        public ?string $canonical_url,
        public mixed $featured_image = null,
    ) {
    }

    public static function fromArray(array $data, string $type = 'post'): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'] ?? null,
            excerpt: $data['excerpt'] ?? null,
            content: $data['content'] ?? null,
            category_id: isset($data['category_id']) && $data['category_id'] !== '' ? (int) $data['category_id'] : null,
            type: $type,
            status: $data['status'] ?? 'draft',
            published_at: $data['published_at'] ?? null,
            is_featured: (bool) ($data['is_featured'] ?? false),
            allow_comments: (bool) ($data['allow_comments'] ?? true),
            order: isset($data['order']) && $data['order'] !== '' ? (int) $data['order'] : 0,
            meta_title: $data['meta_title'] ?? null,
            meta_description: $data['meta_description'] ?? null,
            meta_keywords: $data['meta_keywords'] ?? null,
            canonical_url: $data['canonical_url'] ?? null,
            featured_image: $data['featured_image'] ?? null,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return self::fromArray($request->all() + [
            'featured_image' => $request->file('featured_image'),
        ], $request->route('type', 'post'));
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category_id' => $this->category_id,
            'type' => $this->type,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'is_featured' => $this->is_featured,
            'allow_comments' => $this->allow_comments,
            'order' => $this->order,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'canonical_url' => $this->canonical_url,
        ];
    }
}
