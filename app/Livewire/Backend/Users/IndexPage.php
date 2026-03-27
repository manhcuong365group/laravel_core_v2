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
        $this->executeDeleteAction($singleAction, $bulkAction, User::class, 'người dùng');
    }

    public function bulkStatus(int $isActive, BulkStatusUserAction $action): void
    {
        $this->executeBulkStatus($isActive, $action, 'người dùng');
    }

    public function toggleSelectAll(): void
    {
        $items = $this->getUsersQuery()->limit(100)->get();
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
