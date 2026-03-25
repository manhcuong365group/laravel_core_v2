<?php

namespace App\Livewire\Backend\Roles;

use App\Actions\Role\DeleteRoleAction;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\WithBackendTable;

class IndexPage extends Component
{
    use WithBackendTable;

    public function mount(): void
    {
        $this->authorize('viewAny', Role::class);
    }

    public function executeDelete(DeleteRoleAction $action): void
    {
        $role = Role::findOrFail($this->deleteTargetId);
        $this->authorize('delete', $role);

        if ($role->name === 'super-admin') {
            $this->notify('Không thể xóa vai trò quản trị tối cao!', 'error');
            $this->resetTableState();
            return;
        }

        if ($action->execute($role)) {
            $this->notify('Vai trò đã được xóa thành công!');
        } else {
            $this->notify('Không thể xóa vai trò đang có người dùng!', 'error');
        }
        
        $this->resetTableState();
    }

    public function render()
    {
        $roles = Role::with('permissions', 'users')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->get();
            
        $permissions = Permission::all();

        return view('livewire.backend.roles.index-page', [
            'roles' => $roles,
            'permissions' => $permissions,
        ])->layout('backend.layouts.app', [
            'title' => 'Quản lý vai trò',
        ]);
    }
}
