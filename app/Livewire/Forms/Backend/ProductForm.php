<?php

namespace App\Livewire\Forms\Backend;

use Livewire\Form;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductForm extends Form
{
    public ?Product $product = null;

    public string $name = '';
    public string $slug = '';
    public string $sku = '';
    public string $short_description = '';
    public string $content = '';
    public string $price = '0';
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
    public bool $has_variants = false;
    public array $selected_attributes = [];
    public array $variants = [];
    public $featured_image;
    public $gallery = [];

    public function setProduct(Product $product): void
    {
        $this->product = $product;
        
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->sku = $product->sku ?? '';
        $this->short_description = $product->short_description ?? '';
        $this->content = $product->content ?? '';
        
        $this->price = $product->price ? number_format((float) $product->price, 0, '', '.') : '0';
        $this->sale_price = $product->sale_price ? number_format((float) $product->sale_price, 0, '', '.') : '';
        
        $this->stock_quantity = (string) ($product->stock_quantity ?? '0');
        $this->stock_status = $product->stock_status ?? 'in_stock';
        $this->category_id = (string) ($product->category_id ?? '');
        $this->brand_id = (string) ($product->brand_id ?? '');
        $this->is_active = (bool) $product->is_active;
        $this->is_featured = (bool) $product->is_featured;
        $this->order = (string) ($product->order ?? '0');
        $this->meta_title = $product->meta_title ?? '';
        $this->meta_description = $product->meta_description ?? '';
        $this->meta_keywords = $product->meta_keywords ?? '';
    }

    public function updatedName(): void
    {
        $this->generateSlug();
    }

    public function generateSlug(): void
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function removeFeaturedImage(): void
    {
        $this->featured_image = null;
    }

    public function removeGalleryImage(int $index): void
    {
        if (isset($this->gallery[$index])) {
            array_splice($this->gallery, $index, 1);
        }
    }

    public function normalizeMoney(): void
    {
        $this->price = $this->price !== '' ? preg_replace('/[^\d]/', '', $this->price) : '0';
        $this->sale_price = $this->sale_price !== '' ? preg_replace('/[^\d]/', '', $this->sale_price) : '';
    }

    public function getRules()
    {
        $productId = $this->product?->id;

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
            'has_variants' => 'boolean',
            'variants' => 'exclude_if:has_variants,false|array',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'required_if:has_variants,true|numeric|min:0',
            'variants.*.stock' => 'required_if:has_variants,true|integer|min:0',
            'variants.*.is_active' => 'boolean',
        ];
    }
}
