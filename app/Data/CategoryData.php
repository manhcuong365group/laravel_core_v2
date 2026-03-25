<?php

namespace App\Data;

use Illuminate\Http\Request;

class CategoryData
{
    public function __construct(
        public string $name,
        public ?string $slug,
        public ?string $description,
        public ?int $parent_id,
        public ?int $order,
        public bool $is_active,
        public bool $show_in_menu,
        public ?string $meta_title,
        public ?string $meta_description,
        public ?string $meta_keywords,
        public string $type,
        public mixed $image,
    ) {
    }

    public static function fromArray(array $data, string $type = 'product'): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            parent_id: isset($data['parent_id']) && $data['parent_id'] !== '' ? (int) $data['parent_id'] : null,
            order: isset($data['order']) && $data['order'] !== '' ? (int) $data['order'] : 0,
            is_active: (bool) ($data['is_active'] ?? true),
            show_in_menu: (bool) ($data['show_in_menu'] ?? true),
            meta_title: $data['meta_title'] ?? null,
            meta_description: $data['meta_description'] ?? null,
            meta_keywords: $data['meta_keywords'] ?? null,
            type: $type,
            image: $data['image'] ?? null,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            slug: $request->input('slug'),
            description: $request->input('description'),
            parent_id: $request->input('parent_id') !== null ? (int) $request->input('parent_id') : null,
            order: $request->input('order') !== null ? (int) $request->input('order') : null,
            is_active: $request->boolean('is_active', true),
            show_in_menu: $request->boolean('show_in_menu', true),
            meta_title: $request->input('meta_title'),
            meta_description: $request->input('meta_description'),
            meta_keywords: $request->input('meta_keywords'),
            type: $request->route('type', 'product'),
            image: $request->file('image'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'show_in_menu' => $this->show_in_menu,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'type' => $this->type,
        ];
    }
}
