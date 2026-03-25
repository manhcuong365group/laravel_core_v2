<?php

namespace App\Livewire\Backend\Brands;

use App\Actions\Brand\BulkDeleteBrandAction;
use App\Actions\Brand\BulkStatusBrandAction;
use App\Actions\Brand\DeleteBrandAction;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'order';
    public string $sortDirection = 'asc';
    public array $selectedItems = [];
    public bool $selectAll = false;

    public bool $showDeleteModal = false;
    public ?int $deleteTargetId = null;
    public string $deleteTargetName = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->authorize('viewAny', Brand::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    public function confirmDelete(int $id, string $name): void
    {
        $this->deleteTargetId = $id;
        $this->deleteTargetName = $name;
        $this->showDeleteModal = true;
    }

    public function deleteBrand(DeleteBrandAction $action): void
    {
        if ($this->deleteTargetId) {
            $brand = Brand::find($this->deleteTargetId);
            if ($brand) {
                $this->authorize('delete', $brand);
                $action->execute($brand);
            }
        }

        $this->showDeleteModal = false;
        $this->deleteTargetId = null;
        $this->deleteTargetName = '';
        $this->dispatch('toast', message: 'Xóa thương hiệu thành công.', type: 'success');
    }

    public function deleteSelected(BulkDeleteBrandAction $action): void
    {
        $this->authorize('delete', Brand::class);

        if (empty($this->selectedItems)) {
            return;
        }

        $count = $action->execute($this->selectedItems);
        $this->selectedItems = [];
        $this->selectAll = false;

        $this->dispatch('toast', message: "Đã xóa {$count} thương hiệu đã chọn.", type: 'success');
    }

    public function bulkStatus(int $isActive, BulkStatusBrandAction $action): void
    {
        $this->authorize('update', Brand::class);

        if (empty($this->selectedItems)) {
            return;
        }

        $count = $action->execute($this->selectedItems, (bool) $isActive);

        $this->selectedItems = [];
        $this->selectAll = false;

        $this->dispatch('toast', message: "Đã cập nhật trạng thái {$count} thương hiệu.", type: 'success');
    }

    public function toggleSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedItems = [];
            $this->selectAll = false;
            return;
        }

        $this->selectedItems = $this->getBrandsQuery()
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
        $this->selectAll = true;
    }

    public function updatedSelectedItems(): void
    {
        $selectedCount = count($this->selectedItems);

        if ($selectedCount === 0) {
            $this->selectAll = false;
            return;
        }

        $totalFiltered = (clone $this->getBrandsQuery())->count();
        $this->selectAll = $selectedCount === $totalFiltered;
    }

    private function getBrandsQuery()
    {
        return Brand::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('website', 'like', "%{$this->search}%");
            });
    }

    public function render()
    {
        $brands = $this->getBrandsQuery()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        return view('livewire.backend.brands.index-page', [
            'brands' => $brands,
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý thương hiệu',
        ]);
    }
}

