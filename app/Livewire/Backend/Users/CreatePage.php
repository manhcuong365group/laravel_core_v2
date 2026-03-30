<?php

namespace App\Livewire\Backend\Users;

use App\Actions\User\CreateUserAction;
use App\Data\UserData;
use App\Models\User;
use App\Models\Role;
use App\Traits\WithUserForms;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePage extends Component
{
    use WithFileUploads, WithUserForms;

    public function mount(): void
    {
        $this->authorize('create', User::class);
    }

    protected function rules(): array
    {
        return $this->userRules();
    }

    public function save(CreateUserAction $action)
    {
        $validated = $this->validate();

        try {
            // Map selectedRoles to roles for DTO consistency
            $validated['roles'] = $this->selectedRoles;
            
            $data = UserData::fromArray($validated);
            $action->execute($data);

            session()->flash('success', 'Người dùng đã được tạo thành công!');
            $this->redirect(route('backend.users.index'), navigate: true);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('toast', message: 'Có lỗi xảy ra khi tạo người dùng!', type: 'error');
        }
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.backend.users.create-page', [
            'roles' => $roles,
        ])->layout('backend.layouts.app', [
            'title' => 'Thêm người dùng',
        ]);
    }
}
