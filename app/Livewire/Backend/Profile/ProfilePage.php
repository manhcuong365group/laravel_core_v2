<?php

namespace App\Livewire\Backend\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfilePage extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $address;
    public $avatar;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
    }

    public function updateProfile()
    {
        $user = Auth::user();
        
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        if ($this->avatar) {
            $user->addMedia($this->avatar->getRealPath())
                ->usingFileName($this->avatar->getClientOriginalName())
                ->toMediaCollection('avatar');
        }

        $this->dispatch('toast', message: 'Cập nhật hồ sơ thành công!', type: 'success');
    }

    public function render()
    {
        return view('livewire.backend.profile.profile-page')->layout('backend.layouts.app', ['title' => 'Hồ sơ cá nhân']);
    }
}
