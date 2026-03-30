<?php

namespace App\Data;

use Illuminate\Http\Request;

class CategoryData extends BaseData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public ?int $parent_id = null,
        public ?int $order = 0,
        public bool $is_active = true,
        public bool $show_in_menu = true,
        public ?string $meta_title = null,
        public ?string $meta_description = null,
        public ?string $meta_keywords = null,
        public string $type = 'product',
        public mixed $image = null,
    ) {
    }

    /**
     * Override toArray() to remove image as it's not stored in categories table.
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        unset($data['image']);
        return $data;
    }
}
