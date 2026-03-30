<?php

namespace App\Livewire\Backend\Categories;

use App\Models\Category;
use App\Services\Category\CategoryService;
use App\Data\CategoryData;
use App\Livewire\Forms\Backend\CategoryForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads;

    public CategoryForm $form;

    public function mount(string $type = 'product'): void
    {
        $this->authorize('create', Category::class);
        $this->form->type = $type;
    }

    public function save(CategoryService $service): void
    {
        $this->validate($this->form->rules());

        try {
            $data = CategoryData::fromArray($this->form->all());
            $service->create($data);

            session()->flash('success', 'Danh mục đã được tạo thành công!');
            $this->redirect(route('backend.categories.index', $this->form->type), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo danh mục!', type: 'error');
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
        return view('livewire.backend.categories.create-page', [
            'parentCategories' => $this->form->getParentCategories(),
            'title' => 'Thêm ' . mb_strtolower($this->getBaseTitle()),
        ])->layout('backend.layouts.app', [
            'title' => 'Thêm ' . mb_strtolower($this->getBaseTitle()),
        ]);
    }
}
