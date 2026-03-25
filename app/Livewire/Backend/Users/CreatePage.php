<?php

namespace App\Livewire\Backend\Users;

use App\Actions\User\CreateUserAction;
use App\Data\UserData;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

class CreatePage extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public array $selectedRoles = [];
    public bool $is_active = true;
    public $avatar;

    public function mount(): void
    {
        $this->authorize('create', User::class);
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'selectedRoles' => 'array',
            'selectedRoles.*' => 'exists:roles,name',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|max:2048',
        ];
    }

    public function save(CreateUserAction $action)
    {
        $this->validate();

        $data = new UserData(
            name: $this->name,
            email: $this->email,
            password: $this->password,
            roles: $this->selectedRoles,
            is_active: $this->is_active,
            avatar: $this->avatar
        );

        $action->execute($data);

        $this->dispatch('toast', message: 'Người dùng đã được tạo thành công!', type: 'success');

        return redirect()->route('backend.users.index');
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


