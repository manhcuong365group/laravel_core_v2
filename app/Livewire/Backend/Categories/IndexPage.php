<?php

namespace App\Livewire\Backend\Categories;

use App\Models\Category;
use App\Services\Category\CategoryService;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Traits\WithBackendTable;

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

    public function mount(string $type = 'product'): void
    {
        $this->authorize('viewAny', Category::class);
        $this->type = $type;
        $this->sortField = 'order';
        $this->sortDirection = 'asc';
    }

    #[Computed]
    public function categories()
    {
        return Category::query()
            ->ofType($this->type)
            ->with(['parent', 'media', 'children'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter !== '', fn($q) => $q->where('is_active', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function toggleSelectAll(): void
    {
        $this->tableToggleSelectAll(
            Category::ofType($this->type)->paginate($this->perPage)->items()
        );
    }

    public function executeDelete(CategoryService $service): void
    {
        $this->authorize('delete', Category::class);

        if ($this->selectedItems) {
            $count = count($this->selectedItems);
            $service->bulkDelete($this->selectedItems);
            $this->selectedItems = [];
            $this->notify("Đã xóa {$count} danh mục thành công.");
        }
    }

    public function deleteCategory(int $id, CategoryService $service): void
    {
        $category = Category::findOrFail($id);
        $this->authorize('delete', $category);

        $service->delete($category);
        $this->notify('Đã xóa danh mục thành công.');
    }

    public function toggleStatus(int $id, CategoryService $service): void
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);

        $service->bulkStatus([$id], !$category->is_active);
        $this->notify('Đã cập nhật trạng thái.');
    }

    public function updateField(int $id, string $field, $value, CategoryService $service): void
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);

        $category->update([$field => $value]);
        $this->notify('Đã cập nhật thông tin.');
    }

    public function bulkStatus(int $isActive, CategoryService $service): void
    {
        $this->authorize('update', Category::class);

        if ($this->selectedItems) {
            $count = count($this->selectedItems);
            $service->bulkStatus($this->selectedItems, (bool)$isActive);
            $this->selectedItems = [];
            $this->notify("Đã cập nhật trạng thái cho {$count} danh mục.");
        }
    }

    public function updateOrder(array $orders, CategoryService $service): void
    {
        $this->authorize('update', Category::class);
        $service->reorder($orders);
        $this->notify('Đã cập nhật thứ tự sắp xếp.');
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
        return view('livewire.backend.categories.index-page', [
            'title' => $this->getTitle(),
        ])->layout('backend.layouts.app', [
            'title' => $this->getTitle(),
        ]);
    }
}
