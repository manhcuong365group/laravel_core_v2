<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'sku' => strtoupper(Str::random(8)),
            'short_description' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(100000, 10000000),
            'sale_price' => null,
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'stock_status' => 'in_stock',
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'is_active' => true,
            'is_featured' => false,
            'order' => 0,
        ];
    }
}
