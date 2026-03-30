<?php

namespace App\Data;

use Illuminate\Http\Request;

class BrandData extends BaseData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public ?string $website = null,
        public bool $is_active = true,
        public int $order = 0,
        public mixed $logo = null,
    ) {
    }

    /**
     * Override toArray() to remove logo as it's not stored in brands table.
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        unset($data['logo']);
        return $data;
    }
}
