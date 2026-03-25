<?php

namespace App\Livewire\Backend\Categories;

use App\Actions\Category\UpdateCategoryAction;
use App\Data\CategoryData;
use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPage extends Component
{
    use WithFileUploads;

    public int $categoryId;
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

    public ?string $currentImageUrl = null;

    public function mount(string $type, Category $category): void
    {
        $this->authorize('update', $category);
        
        $this->type = $type;
        $this->categoryId = $category->id;

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

        $this->currentImageUrl = $category->getFirstMediaUrl('image', 'thumb') ?: null;
    }

    public function updatedName(): void
    {
        if (empty($this->slug)) {
            $this->slug = \Illuminate\Support\Str::slug($this->name);
        }

        if (empty($this->meta_title)) {
            $this->meta_title = $this->name;
        }
    }

    public function updatedDescription(): void
    {
        if (empty($this->meta_description)) {
            $this->meta_description = \Illuminate\Support\Str::limit(strip_tags($this->description), 160);
        }
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $this->categoryId,
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

    public function save(UpdateCategoryAction $action): void
    {
        $category = Category::findOrFail($this->categoryId);
        $this->authorize('update', $category);

        $validated = $this->validate();

        $data = CategoryData::fromArray($validated, $this->type);
        $action->execute($category, $data);

        session()->flash('success', 'Danh mục đã được cập nhật!');
        $this->redirect(route('backend.categories.index', $this->type), navigate: true);
    }

    public function getParentCategories(): Collection
    {
        return Category::ofType($this->type)
            ->roots()
            ->where('id', '!=', $this->categoryId)
            ->orderBy('name')
            ->get();
    }

    public function getTitle(): string
    {
        $baseTitle = match ($this->type) {
            'product' => 'Danh mục sản phẩm',
            'news' => 'Danh mục tin tức',
            'photo' => 'Album ảnh',
            default => 'Danh mục',
        };
        return 'Sửa ' . mb_strtolower($baseTitle);
    }

    public function getIcon(): string
    {
        return match ($this->type) {
            'product' => 'ti-package',
            'news' => 'ti-news',
            'photo' => 'ti-camera',
            default => 'ti-category',
        };
    }

    public function render()
    {
        return view('livewire.backend.categories.edit-page', [
            'parentCategories' => $this->getParentCategories(),
            'pageTitle' => $this->getTitle(),
            'pageIcon' => $this->getIcon(),
        ])->layout('backend.layouts.app', [
            'title' => $this->getTitle(),
        ]);
    }
}


