<?php

namespace App\Livewire\Forms\Backend;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?Category $category = null;

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
     * Set data from category instance.
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
        
        $this->type = $category->type;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->parent_id = $category->parent_id;
        $this->order = (string) ($category->order ?? '0');
        $this->is_active = (bool) $category->is_active;
        $this->show_in_menu = (bool) $category->show_in_menu;
        $this->meta_title = $category->meta_title ?? '';
        $this->meta_description = $category->meta_description ?? '';
        $this->meta_keywords = $category->meta_keywords ?? '';
    }

    /**
     * Auto-generate slug and meta title when name is updated.
     */
    public function updatedName(): void
    {
        $this->generateSlug();
        
        if (empty($this->meta_title)) {
            $this->meta_title = $this->name;
        }
    }

    /**
     * Generate slug.
     */
    public function generateSlug(): void
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
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
     * Validation rules.
     */
    public function rules(): array
    {
        $id = $this->category?->id;

        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug' . ($id ? ",$id" : ''),
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
            'show_in_menu' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|string|max:50',
        ];
    }

    /**
     * Get parent categories for the select dropdown.
     */
    public function getParentCategories(): Collection
    {
        return Category::ofType($this->type)
            ->roots()
            ->when($this->category?->id, fn($query) => $query->where('id', '!=', $this->category->id))
            ->orderBy('name')
            ->get();
    }
}
