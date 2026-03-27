<?php

namespace App\Livewire\Backend\Categories;

use App\Actions\Category\CreateCategoryAction;
use App\Data\CategoryData;
use App\Models\Category;
use App\Traits\WithCategoryForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads, WithCategoryForms;

    public function mount(string $type = 'product'): void
    {
        $this->authorize('create', Category::class);
        $this->type = $type;
    }

    protected function rules(): array
    {
        return $this->categoryRules();
    }

    public function save(CreateCategoryAction $action): void
    {
        $validated = $this->validate();

        try {
            $data = CategoryData::fromArray($validated);
            $action->execute($data);

            session()->flash('success', 'Danh mục đã được tạo thành công!');
            $this->redirect(route('backend.categories.index', $this->type), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo danh mục!', type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.backend.categories.create-page', [
            'parentCategories' => $this->getParentCategories(),
            'pageTitle' => $this->getTitle('Thêm'),
            'pageIcon' => $this->getIcon(),
        ])->layout('backend.layouts.app', [
            'title' => $this->getTitle('Thêm'),
        ]);
    }
}
