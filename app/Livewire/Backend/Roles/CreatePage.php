<?php

namespace App\Livewire\Backend\Roles;

use App\Actions\Role\CreateRoleAction;
use App\Data\RoleData;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Throwable;

class CreatePage extends Component
{
    public string $name = '';
    public array $selectedPermissions = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name',
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    public function save(CreateRoleAction $action): void
    {
        $validated = $this->validate();

        try {
            $data = RoleData::fromArray($validated);
            $data->permissions = $this->selectedPermissions;

            $action->execute($data);

            $this->dispatch('toast', message: 'Vai trò đã được tạo thành công!', type: 'success');
            $this->redirect(route('backend.users.roles'), navigate: true);
        } catch (Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo vai trò. Vui lòng thử lại!', type: 'error');
        }
    }

    public function render()
    {
        $permissions = Permission::all();

        return view('livewire.backend.roles.create-page', [
            'permissions' => $permissions,
        ])->layout('backend.layouts.app', [
            'title' => 'Thêm vai trò mới',
        ]);
    }
}
