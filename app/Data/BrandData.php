<?php

namespace App\Data;

use Illuminate\Http\Request;

class BrandData
{
    public function __construct(
        public string $name,
        public ?string $slug,
        public ?string $description,
        public ?string $website,
        public bool $is_active,
        public int $order,
        public mixed $logo,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            website: $data['website'] ?? null,
            is_active: (bool) ($data['is_active'] ?? true),
            order: isset($data['order']) && $data['order'] !== '' ? (int) $data['order'] : 0,
            logo: $data['logo'] ?? null,
        );
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            slug: $request->input('slug'),
            description: $request->input('description'),
            website: $request->input('website'),
            is_active: $request->boolean('is_active', true),
            order: $request->input('order') !== null ? (int) $request->input('order') : 0,
            logo: $request->file('logo'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'website' => $this->website,
            'is_active' => $this->is_active,
            'order' => $this->order,
        ];
    }
}
