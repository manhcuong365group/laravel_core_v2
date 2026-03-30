<?php

namespace App\Livewire\Backend\Categories;

use App\Models\Category;
use App\Services\Category\CategoryService;
use App\Data\CategoryData;
use App\Livewire\Forms\Backend\CategoryForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPage extends Component
{
    use WithFileUploads;

    public CategoryForm $form;
    public ?string $currentImageUrl = null;

    public function mount(string $type, Category $category): void
    {
        $this->authorize('update', $category);
        
        $this->form->setCategory($category);
        $this->currentImageUrl = $category->getFirstMediaUrl('image', 'thumb') ?: null;
    }

    public function save(CategoryService $service): void
    {
        $category = $this->form->category;
        $this->authorize('update', $category);

        $this->validate($this->form->rules());

        try {
            $data = CategoryData::fromArray($this->form->all());
            $service->update($category, $data);

            session()->flash('success', 'Danh mục đã được cập nhật!');
            $this->redirect(route('backend.categories.index', $this->form->type), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật danh mục!', type: 'error');
        }
    }

    public function getBaseTitle(): string
    {
        return match ($this->form->type) {
            'product' => 'Danh mục sản phẩm',
            'news' => 'Danh mục tin tức',
            'photo' => 'Album ảnh',
            default => 'Danh mục',
        };
    }

    public function render()
    {
        return view('livewire.backend.categories.edit-page', [
            'parentCategories' => $this->form->getParentCategories(),
            'title' => 'Sửa ' . mb_strtolower($this->getBaseTitle()),
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa ' . mb_strtolower($this->getBaseTitle()),
        ]);
    }
}
