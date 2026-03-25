<?php

namespace App\Livewire\Backend\Orders;

use App\Actions\Order\BulkDeleteOrderAction;
use App\Actions\Order\BulkStatusOrderAction;
use App\Actions\Order\DeleteOrderAction;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public array $selectedItems = [];
    public bool $selectAll = false;

    public bool $showDeleteModal = false;
    public ?int $deleteTargetId = null;
    public string $deleteTargetCode = '';

    public bool $showBulkStatusModal = false;
    public string $bulkStatusValue = '';
    public string $bulkStatusNote = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '', 'as' => 'status'],
    ];

    public function mount(): void
    {
        $this->authorize('viewAny', Order::class);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
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

    public function confirmDelete(int $id, string $code): void
    {
        $this->deleteTargetId = $id;
        $this->deleteTargetCode = $code;
        $this->showDeleteModal = true;
    }

    public function deleteOrder(DeleteOrderAction $action): void
    {
        if ($this->deleteTargetId) {
            $order = Order::find($this->deleteTargetId);
            if ($order) {
                $this->authorize('delete', $order);
                $action->execute($order);
            }
        }

        $this->showDeleteModal = false;
        $this->deleteTargetId = null;
        $this->deleteTargetCode = '';
        $this->dispatch('toast', message: 'Đã xóa đơn hàng thành công.', type: 'success');
    }

    public function deleteSelected(BulkDeleteOrderAction $action): void
    {
        $this->authorize('delete', Order::class);

        if (empty($this->selectedItems)) {
            return;
        }

        $count = $action->execute($this->selectedItems);
        $this->selectedItems = [];
        $this->selectAll = false;

        $this->dispatch('toast', message: "Đã xóa {$count} đơn hàng đã chọn.", type: 'success');
    }

    public function openBulkStatusModal(): void
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('toast', message: 'Vui lòng chọn ít nhất một đơn hàng.', type: 'warning');
            return;
        }
        $this->showBulkStatusModal = true;
    }

    public function bulkStatus(BulkStatusOrderAction $action): void
    {
        $this->authorize('update', Order::class);

        if (empty($this->selectedItems) || empty($this->bulkStatusValue)) {
            return;
        }

        $count = $action->execute($this->selectedItems, $this->bulkStatusValue, $this->bulkStatusNote);
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->showBulkStatusModal = false;
        $this->bulkStatusValue = '';
        $this->bulkStatusNote = '';

        $this->dispatch('toast', message: "Đã cập nhật trạng thái {$count} đơn hàng.", type: 'success');
    }

    public function toggleSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedItems = [];
            $this->selectAll = false;
            return;
        }

        $this->selectedItems = $this->getOrdersQuery()
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

        $totalFiltered = (clone $this->getOrdersQuery())->count();
        $this->selectAll = $selectedCount === $totalFiltered;
    }

    private function getOrdersQuery()
    {
        return Order::query()
            ->withCount('items')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('code', 'like', "%{$this->search}%")
                        ->orWhere('customer_name', 'like', "%{$this->search}%")
                        ->orWhere('customer_phone', 'like', "%{$this->search}%")
                        ->orWhere('customer_email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $orders = $this->getOrdersQuery()->paginate(20);

        return view('livewire.backend.orders.index-page', [
            'orders' => $orders,
            'statuses' => Order::statusOptions(),
            'title' => 'Quản lý đơn hàng',
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý đơn hàng',
        ]);
    }
}

