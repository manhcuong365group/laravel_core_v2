<?php

namespace App\Traits;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait WithCategoryForms
{
    public string $type = 'product';

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public ?int $parent_id = null;
    public string $order = '0';
    public bool $is_active = true;
    public bool $show_in_menu = true;
    public string $meta_title = '';
    public string $meta_description = '';
    public string $meta_keywords = '';
    public $image;

    /**
     * Common rules for Categories.
     */
    protected function categoryRules(int $ignoreId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug' . ($ignoreId ? ",$ignoreId" : ''),
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'show_in_menu' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Auto-generate slug and meta title when name is updated.
     */
    public function updatedName(): void
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
        
        if (empty($this->meta_title)) {
            $this->meta_title = $this->name;
        }
    }

    /**
     * Auto-generate meta description when description is updated.
     */
    public function updatedDescription(): void
    {
        if (empty($this->meta_description)) {
            $this->meta_description = Str::limit(strip_tags($this->description), 160);
        }
    }

    /**
     * Get parent categories for the select dropdown.
     */
    public function getParentCategories(int $excludeId = null): Collection
    {
        return Category::ofType($this->type)
            ->roots()
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->orderBy('name')
            ->get();
    }

    /**
     * Get page title based on category type.
     */
    public function getTitle(string $action = 'Thêm'): string
    {
        $baseTitle = match ($this->type) {
            'product' => 'Danh mục sản phẩm',
            'news' => 'Danh mục tin tức',
            'photo' => 'Album ảnh',
            default => 'Danh mục',
        };
        return $action . ' ' . mb_strtolower($baseTitle);
    }

    /**
     * Get page icon based on category type.
     */
    public function getIcon(): string
    {
        return match ($this->type) {
            'product' => 'ti-package',
            'news' => 'ti-news',
            'photo' => 'ti-camera',
            default => 'ti-category',
        };
    }
}
