<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Color Attribute
        $color = Attribute::create([
            'name' => 'Màu sắc',
            'type' => 'color',
            'display_order' => 1,
        ]);

        $color->values()->createMany([
            ['value' => 'Trắng', 'meta_value' => '#FFFFFF', 'display_order' => 1],
            ['value' => 'Đen', 'meta_value' => '#000000', 'display_order' => 2],
            ['value' => 'Đỏ', 'meta_value' => '#FF0000', 'display_order' => 3],
            ['value' => 'Xanh dương', 'meta_value' => '#0000FF', 'display_order' => 4],
            ['value' => 'Vàng', 'meta_value' => '#FFFF00', 'display_order' => 5],
        ]);

        // 2. Size Attribute
        $size = Attribute::create([
            'name' => 'Kích cỡ',
            'type' => 'button',
            'display_order' => 2,
        ]);

        $size->values()->createMany([
            ['value' => 'S', 'display_order' => 1],
            ['value' => 'M', 'display_order' => 2],
            ['value' => 'L', 'display_order' => 3],
            ['value' => 'XL', 'display_order' => 4],
            ['value' => 'XXL', 'display_order' => 5],
        ]);

        // 3. Material
        $material = Attribute::create([
            'name' => 'Chất liệu',
            'type' => 'button',
            'display_order' => 3,
        ]);

        $material->values()->createMany([
            ['value' => 'Cotton', 'display_order' => 1],
            ['value' => 'Polyester', 'display_order' => 2],
            ['value' => 'Len', 'display_order' => 3],
            ['value' => 'Lụa', 'display_order' => 4],
        ]);
    }
}
