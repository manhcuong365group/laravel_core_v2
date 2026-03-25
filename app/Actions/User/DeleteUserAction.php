<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeleteUserAction
{
    public function execute(User $user): bool
    {
        if ($user->id === Auth::id()) {
            return false;
        }

        return $user->delete();
    }
}
