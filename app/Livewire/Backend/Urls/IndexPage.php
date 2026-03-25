<?php

namespace App\Livewire\Backend\Urls;

use App\Actions\Url\BulkDeleteUrlAction;
use App\Actions\Url\BulkStatusUrlAction;
use App\Actions\Url\DeleteUrlAction;
use App\Models\Url;
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
    public bool $isBulkDelete = false;
    public ?int $deleteTargetId = null;
    public string $deleteTargetName = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => '', 'as' => 'status'],
    ];

    public function mount(): void
    {
        $this->authorize('viewAny', Url::class);
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

    public function confirmDelete(int $id, string $name): void
    {
        $this->isBulkDelete = false;
        $this->deleteTargetId = $id;
        $this->deleteTargetName = $name;
        $this->showDeleteModal = true;
    }

    public function confirmBulkDelete(): void
    {
        if (empty($this->selectedItems)) {
            return;
        }
        $this->isBulkDelete = true;
        $this->showDeleteModal = true;
    }

    public function toggleStatus(int $id, BulkStatusUrlAction $action): void
    {
        $url = Url::findOrFail($id);
        $this->authorize('update', $url);
        
        $action->execute([$id], !$url->is_active);
        
        $this->dispatch('toast', message: 'Đã cập nhật trạng thái.', type: 'success');
    }

    public function deleteUrl(DeleteUrlAction $action): void
    {
        if ($this->deleteTargetId) {
            $url = Url::find($this->deleteTargetId);
            if ($url) {
                $this->authorize('delete', $url);
                $action->execute($url);
            }
        }

        $this->showDeleteModal = false;
        $this->deleteTargetId = null;
        $this->deleteTargetName = '';
        $this->dispatch('toast', message: 'Đã xóa URL thành công.', type: 'success');
    }

    public function deleteSelected(BulkDeleteUrlAction $action): void
    {
        $this->authorize('delete', Url::class);

        if (empty($this->selectedItems)) {
            return;
        }

        $action->execute($this->selectedItems);
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->showDeleteModal = false;

        $this->dispatch('toast', message: "Đã xóa các URL đã chọn.", type: 'success');
    }

    public function scanSite(\App\Actions\Url\ScanProjectUrlsAction $action): void
    {
        $this->authorize('create', Url::class);
        
        $stats = $action->execute();
        
        $message = "Đã quét xong! Import {$stats['imported']} link mới, bỏ qua {$stats['existing']} link đã có.";
        $this->dispatch('toast', message: $message, type: 'success');
    }

    private function getUrlsQuery()
    {
        return Url::query()
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('title', 'like', "%{$this->search}%")
                        ->orWhere('original_url', 'like', "%{$this->search}%")
                        ->orWhere('short_url', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter !== '', fn($query) => $query->where('is_active', $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $urls = $this->getUrlsQuery()->paginate(20);

        return view('livewire.backend.urls.index-page', [
            'urls' => $urls,
            'title' => 'Quản lý URL',
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý URL',
        ]);
    }
}
