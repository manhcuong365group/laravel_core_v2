<?php

namespace App\Livewire\Backend\Brands;

use App\Actions\Brand\BulkDeleteBrandAction;
use App\Actions\Brand\BulkStatusBrandAction;
use App\Actions\Brand\DeleteBrandAction;
use App\Models\Brand;
use App\Traits\WithBackendTable;
use Livewire\Component;

class IndexPage extends Component
{
    use WithBackendTable;

    protected $queryString = [
        'search'        => ['except' => ''],
        'statusFilter'  => ['except' => '', 'as' => 'status'],
        'sortField'     => ['except' => 'order'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount(): void
    {
        $this->authorize('viewAny', Brand::class);
        $this->sortField = 'order';
        $this->sortDirection = 'asc';
    }

    public function toggleSelectAll(): void
    {
        $this->tableToggleSelectAll($this->getBrandsQuery()->get());
    }

    public function executeDelete(DeleteBrandAction $deleteAction, BulkDeleteBrandAction $bulkDeleteAction): void
    {
        $this->executeDeleteAction($deleteAction, $bulkDeleteAction, Brand::class, 'thương hiệu');
    }

    public function toggleStatus(int $id, BulkStatusBrandAction $action): void
    {
        $this->executeToggleStatus($id, Brand::class, $action);
    }

    public function bulkStatus(int $isActive, BulkStatusBrandAction $action): void
    {
        $this->executeBulkStatus($isActive, $action, 'thương hiệu');
    }

    public function updateField(int $id, string $field, $value): void
    {
        $this->executeUpdateField($id, $field, $value, Brand::class);
    }

    private function getBrandsQuery()
    {
        return Brand::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('website', 'like', "%{$this->search}%"))
            ->when($this->statusFilter !== '', fn($q) => $q->where('is_active', $this->statusFilter));
    }

    public function render()
    {
        $brands = $this->getBrandsQuery()
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.brands.index-page', [
            'brands' => $brands,
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý thương hiệu',
        ]);
    }
}
