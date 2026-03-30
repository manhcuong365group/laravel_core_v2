<?php

namespace App\Livewire\Backend\Users;

use App\Actions\User\UpdateUserAction;
use App\Data\UserData;
use App\Models\User;
use App\Models\Role;
use App\Traits\WithUserForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPage extends Component
{
    use WithFileUploads, WithUserForms;

    public int $userId;

    public function mount(User $user): void
    {
        $this->authorize('update', $user);
        
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = (bool) $user->is_active;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    protected function rules(): array
    {
        return $this->userRules($this->userId);
    }

    public function save(UpdateUserAction $action)
    {
        $user = User::findOrFail($this->userId);
        $this->authorize('update', $user);

        $validated = $this->validate();

        try {
            // Map selectedRoles to roles for DTO consistency
            $validated['roles'] = $this->selectedRoles;
            
            $data = UserData::fromArray($validated);
            $action->execute($user, $data);

            session()->flash('success', 'Người dùng đã được cập nhật thành công!');
            $this->redirect(route('backend.users.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi cập nhật người dùng!', type: 'error');
        }
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.backend.users.edit-page', [
            'roles' => $roles,
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa người dùng',
        ]);
    }
}
