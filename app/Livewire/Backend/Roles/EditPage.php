<?php

namespace App\Livewire\Backend\Roles;

use App\Actions\Role\UpdateRoleAction;
use App\Data\RoleData;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Throwable;

class EditPage extends Component
{
    public Role $role;
    public string $name = '';
    public array $selectedPermissions = [];

    public function mount(Role $role): void
    {
        $this->authorize('update', $role);
        $this->role = $role;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->role->id),
            ],
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    public function save(UpdateRoleAction $action): void
    {
        $validated = $this->validate();

        try {
            $data = RoleData::fromArray($validated);
            $data->permissions = $this->selectedPermissions;

            $action->execute($this->role, $data);

            $this->dispatch('toast', message: 'Vai trò đã được cập nhật thành công!', type: 'success');
            $this->redirect(route('backend.users.roles'), navigate: true);
        } catch (Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật vai trò. Vui lòng thử lại!', type: 'error');
        }
    }

    public function render()
    {
        $permissions = Permission::all();

        return view('livewire.backend.roles.edit-page', [
            'permissions' => $permissions,
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa vai trò: ' . $this->role->name,
        ]);
    }
}
