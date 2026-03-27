<?php

namespace App\Livewire\Backend\Orders;

use App\Actions\Order\BulkDeleteOrderAction;
use App\Actions\Order\BulkStatusOrderAction;
use App\Actions\Order\DeleteOrderAction;
use App\Models\Order;
use App\Traits\WithBackendTable;
use Livewire\Component;

class IndexPage extends Component
{
    use WithBackendTable;

    public string $bulkStatusNote = '';
    public string $bulkStatusValue = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '', 'as' => 'status'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        $this->authorize('viewAny', Order::class);
    }

    /**
     * Get the orders query for the table.
     */
    public function getOrdersQuery()
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

    public function toggleSelectAll(): void
    {
        $this->tableToggleSelectAll($this->getOrdersQuery()->get());
    }

    public function executeDelete(DeleteOrderAction $deleteAction, BulkDeleteOrderAction $bulkDeleteAction): void
    {
        $this->executeDeleteAction($deleteAction, $bulkDeleteAction, Order::class, 'đơn hàng');
    }

    /**
     * Override bulk status update specifically for Orders (needs value & note).
     */
    public function bulkStatus(BulkStatusOrderAction $action): void
    {
        $this->authorize('update', Order::class);

        if (empty($this->selectedItems) || empty($this->bulkStatusValue)) {
            $this->notify('Vui lòng chọn ít nhất một đơn hàng và trạng thái mới.', 'warning');
            return;
        }

        $count = $action->execute($this->selectedItems, $this->bulkStatusValue, $this->bulkStatusNote);
        
        $this->resetSelection();
        $this->bulkStatusValue = '';
        $this->bulkStatusNote = '';

        $this->notify("Đã cập nhật trạng thái {$count} đơn hàng.");
    }

    public function render()
    {
        $orders = $this->getOrdersQuery()->paginate($this->perPage);

        return view('livewire.backend.orders.index-page', [
            'orders' => $orders,
            'statuses' => Order::statusOptions(),
            'title' => 'Quản lý đơn hàng',
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý đơn hàng',
        ]);
    }
}
