<?php

namespace App\Livewire\Backend\Newsletters;

use App\Actions\Newsletter\BulkDeleteSubscriberAction;
use App\Actions\Newsletter\BulkStatusSubscriberAction;
use App\Actions\Newsletter\CreateSubscriberAction;
use App\Actions\Newsletter\DeleteSubscriberAction;
use App\Actions\Newsletter\UpdateSubscriberAction;
use App\Data\NewsletterData;
use App\Models\ActivityLog;
use App\Models\Subscriber;
use App\Traits\WithBackendTable;
use Livewire\Component;

class IndexPage extends Component
{
    use WithBackendTable;

    public string $status = '';

    // Modal Create/Edit
    public bool $showEditModal = false;
    public ?int $subscriberId = null;
    public array $formData = [
        'name' => '',
        'email' => '',
        'status' => 'subscribed',
        'source' => '',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->authorize('viewAny', Subscriber::class);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset('subscriberId', 'formData');
        $this->formData['status'] = 'subscribed';
        $this->showEditModal = true;
    }

    public function openEditModal(int $id): void
    {
        $subscriber = Subscriber::findOrFail($id);
        $this->authorize('update', $subscriber);
        
        $this->subscriberId = $subscriber->id;
        $this->formData = [
            'name' => $subscriber->name,
            'email' => $subscriber->email,
            'status' => $subscriber->status,
            'source' => $subscriber->source,
        ];
        $this->showEditModal = true;
    }

    public function saveSubscriber(CreateSubscriberAction $createAction, UpdateSubscriberAction $updateAction): void
    {
        $this->validate([
            'formData.name' => 'nullable|string|max:255',
            'formData.email' => 'required|email|max:255|unique:subscribers,email,' . ($this->subscriberId ?? 'NULL') . ',id',
            'formData.status' => 'required|in:subscribed,unsubscribed',
            'formData.source' => 'nullable|string|max:255',
        ]);

        $data = NewsletterData::fromArray($this->formData);

        if ($this->subscriberId) {
            $subscriber = Subscriber::findOrFail($this->subscriberId);
            $this->authorize('update', $subscriber);
            $updateAction->execute($subscriber, $data);
            $this->notify('Người đăng ký đã được cập nhật.');
        } else {
            $this->authorize('create', Subscriber::class);
            $createAction->execute($data);
            $this->notify('Người đăng ký mới đã được tạo.');
        }

        $this->showEditModal = false;
    }

    public function executeDelete(DeleteSubscriberAction $action, BulkDeleteSubscriberAction $bulkAction): void
    {
        $this->executeDeleteAction($action, $bulkAction, Subscriber::class, 'người đăng ký');
    }

    public function bulkStatus(string $newStatus, BulkStatusSubscriberAction $action): void
    {
        $this->authorize('update', Subscriber::class);

        if (empty($this->selectedItems)) {
            $this->notify('Vui lòng chọn ít nhất một người đăng ký.', 'warning');
            return;
        }

        $count = $action->execute($this->selectedItems, $newStatus);
        $this->resetTableState();
        $this->notify("Đã cập nhật trạng thái cho {$count} người đăng ký.");
    }

    public function toggleSelectAll(): void
    {
        $items = Subscriber::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('email', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->limit(100)
            ->get();

        $this->tableToggleSelectAll($items);
    }

    public function export()
    {
        $this->authorize('viewAny', Subscriber::class);

        $rows = Subscriber::query()
            ->when($this->status, fn($query) => $query->where('status', $this->status))
            ->orderBy('id')
            ->get(['id', 'name', 'email', 'status', 'source', 'subscribed_at', 'unsubscribed_at', 'created_at']);

        ActivityLog::log('newsletters.export', null, [
            'count' => $rows->count(),
            'status_filter' => $this->status ?: null,
        ]);

        $filename = 'subscribers_' . now()->format('Ymd_His') . '.csv';

        $callback = function () use ($rows): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['ID', 'Name', 'Email', 'Status', 'Source', 'Subscribed At', 'Unsubscribed At', 'Created At']);

            foreach ($rows as $row) {
                fputcsv($output, [
                    $row->id,
                    $row->name,
                    $row->email,
                    $row->status,
                    $row->source,
                    optional($row->subscribed_at)->format('Y-m-d H:i:s'),
                    optional($row->unsubscribed_at)->format('Y-m-d H:i:s'),
                    optional($row->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($output);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    public function render()
    {
        $subscribers = Subscriber::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('email', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                });
            })
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.backend.newsletters.index-page', [
            'subscribers' => $subscribers,
            'statuses' => [
                'subscribed' => 'Đã đăng ký',
                'unsubscribed' => 'Đã hủy đăng ký',
            ],
        ])->layout('backend.layouts.app', [
            'title' => 'Người đăng ký bản tin',
        ]);
    }
}
