<?php

namespace App\Traits;

use Livewire\WithPagination;

trait WithBackendTable
{
    use WithPagination;

    // Filter properties
    public string $search = '';
    public string $statusFilter = '';
    public string $categoryFilter = '';
    
    // Pagination & Sorting properties
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 25;

    // Bulk action properties
    public array $selectedItems = [];
    public bool $selectAll = false;

    // Modal state for deletions
    public bool $showDeleteModal = false;
    public bool $isBulkDelete = false;
    public ?int $deleteTargetId = null;
    public string $deleteTargetName = '';

    /**
     * Handle sorting.
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /**
     * Resets selection when filters/search change.
     */
    public function updatedSearch(): void { $this->resetPage(); $this->resetSelection(); }
    public function updatedStatusFilter(): void { $this->resetPage(); $this->resetSelection(); }
    public function updatedCategoryFilter(): void { $this->resetPage(); $this->resetSelection(); }

    /**
     * Helper to clear selection.
     */
    protected function resetSelection(): void
    {
        $this->selectedItems = [];
        $this->selectAll = false;
    }

    /**
     * Toggle "Select All" items on current search criteria.
     * Note: This must be passed the query result that matches current filters.
     */
    public function tableToggleSelectAll(mixed $queryItems): void
    {
        if ($this->selectAll) {
            $this->selectedItems = [];
            $this->selectAll = false;
            return;
        }

        $this->selectedItems = $queryItems->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
        $this->selectAll = true;
    }

    /**
     * Open confirmation modal for single item deletion.
     */
    public function confirmDelete(int $id, string $name): void
    {
        $this->deleteTargetId = $id;
        $this->deleteTargetName = $name;
        $this->isBulkDelete = false;
        $this->showDeleteModal = true;
    }

    /**
     * Open confirmation modal for bulk deletion.
     */
    public function confirmBulkDelete(): void
    {
        if (empty($this->selectedItems)) {
            $this->notify('Vui lòng chọn ít nhất một mục để xóa.', 'warning');
            return;
        }

        $this->isBulkDelete = true;
        $this->showDeleteModal = true;
    }

    /**
     * Resets table state (selection and modals).
     */
    public function resetTableState(): void
    {
        $this->resetSelection();
        $this->showDeleteModal = false;
        $this->isBulkDelete = false;
        $this->deleteTargetId = null;
        $this->deleteTargetName = '';
    }

    /**
     * Standard implementation of toggleSelectAll.
     */
    public function toggleSelectAll(mixed $items): void
    {
        if ($this->selectAll) {
            $this->selectedItems = [];
            $this->selectAll = false;
            return;
        }

        $this->selectedItems = $items->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
        $this->selectAll = true;
    }

    /**
     * Standard implementation for bulk status updates.
     */
    public function executeBulkStatus(int $isActive, mixed $action, string $label = 'mục'): void
    {
        if (empty($this->selectedItems)) {
            $this->notify("Vui lòng chọn ít nhất một {$label}.", 'warning');
            return;
        }

        $count = $action->execute($this->selectedItems, (bool) $isActive);
        $this->resetTableState();
        $this->notify("Đã cập nhật trạng thái của {$count} {$label} thành công.");
    }

    /**
     * Standard implementation for single status toggle.
     */
    public function executeToggleStatus(int $id, mixed $model, mixed $action): void
    {
        $item = $model::findOrFail($id);
        \Illuminate\Support\Facades\Gate::authorize('update', $item);

        $action->execute([$id], !$item->is_active);
        $this->notify('Đã cập nhật trạng thái.');
    }

    /**
     * Standard implementation for updating a single field in a model.
     */
    public function executeUpdateField(int $id, string $field, mixed $value, mixed $model): void
    {
        $item = $model::findOrFail($id);
        \Illuminate\Support\Facades\Gate::authorize('update', $item);

        // Normalize numeric values
        if (in_array($field, ['order', 'price', 'sale_price', 'quantity'])) {
            $value = preg_replace('/[^\d]/', '', (string)$value);
        }

        $item->update([$field => $value]);
        $this->notify('Đã cập nhật dữ liệu.');
    }

    /**
     * Standard implementation for deleting items.
     */
    public function executeDeleteAction(mixed $deleteAction, mixed $bulkDeleteAction, mixed $model, string $label = 'mục'): void
    {
        if ($this->isBulkDelete) {
            if (empty($this->selectedItems)) return;
            $bulkDeleteAction->execute($this->selectedItems);
            $this->notify("Đã xóa vĩnh viễn các {$label} được chọn thành công.");
        } else {
            if (!$this->deleteTargetId) return;
            $item = $model::findOrFail($this->deleteTargetId);
            $deleteAction->execute($item);
            $this->notify("{$label} '{$this->deleteTargetName}' đã được xóa vĩnh viễn.");
        }

        $this->resetTableState();
    }

    /**
     * Global toast notification helper.
     */
    public function notify(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', message: $message, type: $type);
    }
}
