<?php

namespace App\Data;

use Illuminate\Http\Request;

class UrlData extends BaseData
{
    public function __construct(
        public ?string $title = null,
        public string $original_url,
        public ?string $short_url = null,
        public bool $is_active = true,
        public ?string $description = null,
        public int $click_count = 0,
    ) {}

    public static function fromRequest(Request $request): static
    {
        return static::fromArray($request->all());
    }
}
