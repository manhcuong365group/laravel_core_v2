<?php

namespace App\Livewire\Backend\Categories;

use App\Actions\Category\CreateCategoryAction;
use App\Data\CategoryData;
use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads;

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

    public function mount(string $type = 'product'): void
    {
        $this->authorize('create', Category::class);
        $this->type = $type;
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
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

    public function save(CreateCategoryAction $action): void
    {
        $validated = $this->validate();

        $data = CategoryData::fromArray($validated, $this->type);
        $action->execute($data);

        session()->flash('success', 'Danh mục đã được tạo thành công!');
        $this->redirect(route('backend.categories.index', $this->type), navigate: true);
    }

    public function getParentCategories(): Collection
    {
        return Category::ofType($this->type)
            ->roots()
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
        return 'Thêm ' . mb_strtolower($baseTitle);
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
        return view('livewire.backend.categories.create-page', [
            'parentCategories' => $this->getParentCategories(),
            'pageTitle' => $this->getTitle(),
            'pageIcon' => $this->getIcon(),
        ])->layout('backend.layouts.app', [
            'title' => $this->getTitle(),
        ]);
    }
}


