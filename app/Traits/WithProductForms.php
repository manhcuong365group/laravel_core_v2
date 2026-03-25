<?php

namespace App\Traits;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait WithProductForms
{
    // Form fields
    public string $name = '';
    public string $slug = '';
    public string $sku = '';
    public string $short_description = '';
    public string $content = '';
    public string $price = '';
    public string $sale_price = '';
    public string $stock_quantity = '0';
    public string $stock_status = 'in_stock';
    public ?string $category_id = null;
    public ?string $brand_id = null;
    public bool $is_active = true;
    public bool $is_featured = false;
    public string $order = '0';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $meta_keywords = '';
    public $featured_image;
    public $gallery = [];

    /**
     * Set up common product rules.
     */
    protected function productRules(mixed $productId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug' . ($productId ? ',' . $productId : ''),
            'sku' => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Auto-generate slug when name changes.
     */
    public function updatedName(): void
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    /**
     * Normalize money input (remove separators).
     */
    protected function normalizeMoney(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $digits = preg_replace('/[^\d]/', '', (string)$value);
        return $digits !== '' ? $digits : null;
    }

    /**
     * Get product categories.
     */
    public function getCategories(): Collection
    {
        return Category::ofType('product')
            ->active()
            ->orderBy('name')
            ->get();
    }

    /**
     * Get brands.
     */
    public function getBrands(): Collection
    {
        return Brand::active()
            ->orderBy('name')
            ->get();
    }

    public function removeFeaturedImage(): void
    {
        $this->featured_image = null;
    }

    /**
     * Remove an image from the gallery.
     */
    public function removeGalleryImage(int $index): void
    {
        if (isset($this->gallery[$index])) {
            array_splice($this->gallery, $index, 1);
        }
    }
}
