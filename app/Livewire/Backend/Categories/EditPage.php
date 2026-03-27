<?php

namespace App\Livewire\Backend\Categories;

use App\Actions\Category\UpdateCategoryAction;
use App\Data\CategoryData;
use App\Models\Category;
use App\Traits\WithCategoryForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPage extends Component
{
    use WithFileUploads, WithCategoryForms;

    public int $categoryId;
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

    protected function rules(): array
    {
        return $this->categoryRules($this->categoryId);
    }

    public function save(UpdateCategoryAction $action): void
    {
        $category = Category::findOrFail($this->categoryId);
        $this->authorize('update', $category);

        $validated = $this->validate();

        try {
            $data = CategoryData::fromArray($validated);
            $action->execute($category, $data);

            session()->flash('success', 'Danh mục đã được cập nhật!');
            $this->redirect(route('backend.categories.index', $this->type), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật danh mục!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.categories.edit-page', [
            'parentCategories' => $this->getParentCategories($this->categoryId),
            'pageTitle' => $this->getTitle('Sửa'),
            'pageIcon' => $this->getIcon(),
        ])->layout('backend.layouts.app', [
            'title' => $this->getTitle('Sửa'),
        ]);
    }
}
