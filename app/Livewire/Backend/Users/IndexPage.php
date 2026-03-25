<?php

namespace App\Livewire\Backend\Users;

use App\Actions\User\BulkDeleteUserAction;
use App\Actions\User\BulkStatusUserAction;
use App\Actions\User\DeleteUserAction;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Traits\WithBackendTable;
use Illuminate\Database\Eloquent\Builder;

class IndexPage extends Component
{
    use WithBackendTable;

    public string $roleFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->authorize('viewAny', User::class);
    }

    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function executeDelete(DeleteUserAction $singleAction, BulkDeleteUserAction $bulkAction): void
    {
        if ($this->isBulkDelete) {
            $this->authorize('delete', User::class);
            if (empty($this->selectedItems)) return;

            $ids = collect($this->selectedItems)->map(fn($id) => (int) $id)->toArray();
            $count = $bulkAction->execute($ids);

            if ($count === 0) {
                $this->notify('Không thể xóa chính tài khoản của bạn.', 'error');
            } else {
                $this->notify("Đã xóa {$count} người dùng đã chọn.");
                $this->resetTableState();
            }
        } else {
            if ($this->deleteTargetId) {
                $user = User::findOrFail($this->deleteTargetId);
                $this->authorize('delete', $user);
                
                if (!$singleAction->execute($user)) {
                    $this->notify('Không thể xóa tài khoản của chính mình!', 'error');
                } else {
                    $this->notify('Xóa người dùng thành công.');
                    $this->resetTableState();
                }
            }
        }
    }

    public function bulkStatus(int $isActive, BulkStatusUserAction $action): void
    {
        $this->authorize('update', User::class);

        if (empty($this->selectedItems)) {
            $this->notify('Vui lòng chọn ít nhất một người dùng.', 'warning');
            return;
        }

        $ids = collect($this->selectedItems)->map(fn($id) => (int) $id)->toArray();
        $updated = $action->execute($ids, (bool) $isActive);

        if ($updated === 0) {
             $this->notify('Không có người dùng hợp lệ để cập nhật.', 'error');
        } else {
             $this->notify("Đã cập nhật trạng thái {$updated} người dùng.");
             $this->resetTableState();
        }
    }

    public function toggleSelectAll(): void
    {
        $items = $this->getUsersQuery()
            ->limit(100)
            ->get();

        $this->tableToggleSelectAll($items);
    }

    public function getUsersQuery(): Builder
    {
        return User::query()
            ->with(['roles', 'media'])
            ->where('id', '!=', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($sq) {
                    $sq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->roleFilter, fn($q) => $q->whereHas('roles', fn($rq) => $rq->where('name', $this->roleFilter)));
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'roleFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $users = $this->getUsersQuery()
            ->latest()
            ->paginate($this->perPage);

        $roles = Role::all();

        return view('livewire.backend.users.index-page', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý người dùng',
        ]);
    }
}
