<?php

namespace App\Livewire\Backend\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SecurityPage extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        
        $this->dispatch('toast', message: 'Đổi mật khẩu thành công!', type: 'success');
    }

    public function render()
    {
        return view('livewire.backend.profile.security-page')->layout('backend.layouts.app', ['title' => 'Bảo mật tài khoản']);
    }
}
