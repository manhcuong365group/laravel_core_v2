<?php

namespace App\Livewire\Backend\Users;

use App\Actions\User\UpdateUserAction;
use App\Data\UserData;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

class EditPage extends Component
{
    use WithFileUploads;

    public User $user;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $selectedRoles = [];
    public bool $is_active = true;
    public $avatar;

    public function mount(User $user): void
    {
        $this->authorize('update', $user);
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = (bool) $user->is_active;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'selectedRoles' => 'array',
            'selectedRoles.*' => 'exists:roles,name',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|max:2048',
        ];
    }

    public function save(UpdateUserAction $action)
    {
        $this->validate();

        $data = new UserData(
            name: $this->name,
            email: $this->email,
            password: $this->password ?: null,
            roles: $this->selectedRoles,
            is_active: $this->is_active,
            avatar: $this->avatar
        );

        $action->execute($this->user, $data);

        $this->dispatch('toast', message: 'Người dùng đã được cập nhật thành công!', type: 'success');

        return redirect()->route('backend.users.index');
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.backend.users.edit-page', [
            'roles' => $roles,
        ])->layout('backend.layouts.app', [
            'title' => 'Sửa người dùng: ' . $this->user->name,
        ]);
    }
}


