<?php

namespace App\Data;

use Illuminate\Http\Request;

class ProductData extends BaseData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $sku = null,
        public ?string $short_description = null,
        public ?string $content = null,
        public float $price = 0,
        public ?float $sale_price = null,
        public ?int $stock_quantity = null,
        public string $stock_status = 'in_stock',
        public ?int $category_id = null,
        public ?int $brand_id = null,
        public bool $is_active = true,
        public bool $is_featured = false,
        public ?int $order = null,
        public ?string $meta_title = null,
        public ?string $meta_description = null,
        public ?string $meta_keywords = null,
        public mixed $featured_image = null,
        public mixed $gallery = [],
    ) {}

    public static function fromRequest(Request $request): static
    {
        return static::fromArray($request->all() + [
            'featured_image' => $request->file('featured_image'),
            'gallery' => $request->file('gallery', []),
        ]);
    }

    /**
     * Chỉ trả về các trường thuộc DB table products.
     * Loại bỏ featured_image và gallery (xử lý riêng qua MediaService).
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        unset($data['featured_image'], $data['gallery']);

        return $data;
    }
}

