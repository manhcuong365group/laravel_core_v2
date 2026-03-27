<?php

namespace App\Livewire\Backend\Categories;

use App\Actions\Category\BulkDeleteCategoryAction;
use App\Actions\Category\BulkStatusCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Traits\WithBackendTable;
use App\Models\Category;
use Livewire\Component;

class IndexPage extends Component
{
    use WithBackendTable;

    public string $type = 'product';

    protected $queryString = [
        'search'        => ['except' => ''],
        'statusFilter'  => ['except' => '', 'as' => 'status'],
        'sortField'     => ['except' => 'order'],
        'sortDirection' => ['except' => 'asc'],
    ];


    public function toggleSelectAll(): void
    {
        $this->tableToggleSelectAll($this->getCategoriesQuery()->get());
    }

    public function mount(string $type = 'product'): void
    {
        $this->authorize('viewAny', Category::class);
        $this->type = $type;
        $this->sortField = 'order';
        $this->sortDirection = 'asc';
    }


    public function executeDelete(DeleteCategoryAction $deleteAction, BulkDeleteCategoryAction $bulkDeleteAction): void
    {
        $this->executeDeleteAction($deleteAction, $bulkDeleteAction, Category::class, 'danh mục');
    }

    public function toggleStatus(int $id, BulkStatusCategoryAction $action): void
    {
        $this->executeToggleStatus($id, Category::class, $action);
    }

    public function updateField(int $id, string $field, $value): void
    {
        $this->executeUpdateField($id, $field, $value, Category::class);
    }

    public function bulkStatus(int $isActive, BulkStatusCategoryAction $action): void
    {
        $this->executeBulkStatus($isActive, $action, 'danh mục');
    }


    protected function getCategoriesQuery()
    {
        return Category::query()
            ->ofType($this->type)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter !== '', fn($q) => $q->where('is_active', $this->statusFilter));
    }

    public function getTitle(): string
    {
        return match ($this->type) {
            'product' => 'Danh mục sản phẩm',
            'news' => 'Danh mục tin tức',
            'photo' => 'Album ảnh',
            default => 'Danh mục',
        };
    }

    public function render()
    {
        $categories = $this->getCategoriesQuery()
            ->with(['parent'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        return view('livewire.backend.categories.index-page', [
            'categories' => $categories,
            'title' => $this->getTitle(),
        ])->layout('backend.layouts.app', [
            'title' => $this->getTitle(),
        ]);
    }
}

