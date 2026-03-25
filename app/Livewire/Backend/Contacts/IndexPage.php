<?php

namespace App\Livewire\Backend\Contacts;

use App\Actions\Contact\BulkDeleteContactAction;
use App\Actions\Contact\BulkStatusContactAction;
use App\Actions\Contact\DeleteContactAction;
use App\Models\Contact;
use Livewire\Component;

class IndexPage extends Component
{
    use \App\Traits\WithBackendTable;

    public function mount(): void
    {
        $this->authorize('viewAny', Contact::class);
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '', 'as' => 'status'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    /**
     * Get the contacts query for the table.
     */
    public function getContactsQuery()
    {
        return Contact::query()
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                        ->orWhere('subject', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function toggleSelectAll(): void
    {
        $this->tableToggleSelectAll($this->getContactsQuery()->get());
    }

    public function executeDelete(DeleteContactAction $deleteAction, BulkDeleteContactAction $bulkDeleteAction): void
    {
        $this->executeDeleteAction($deleteAction, $bulkDeleteAction, Contact::class, 'liên hệ');
    }

    public function bulkStatus(string $newStatus, BulkStatusContactAction $action): void
    {
        $this->authorize('update', Contact::class);

        if (empty($this->selectedItems)) {
            return;
        }

        $count = $action->execute($this->selectedItems, $newStatus);
        $this->resetSelection();

        $this->notify("Đã cập nhật trạng thái cho {$count} liên hệ.");
    }

    public function render()
    {
        $contacts = $this->getContactsQuery()->paginate($this->perPage);

        return view('livewire.backend.contacts.index-page', [
            'contacts' => $contacts,
            'statuses' => [
                'new' => 'Mới',
                'read' => 'Đã đọc',
                'replied' => 'Đã phản hồi',
            ],
            'title' => 'Hộp thư liên hệ',
        ])->layout('backend.layouts.app', [
            'title' => 'Hộp thư liên hệ',
        ]);
    }
}
