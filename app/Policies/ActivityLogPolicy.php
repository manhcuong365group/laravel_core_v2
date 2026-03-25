<?php

namespace App\Policies;

use App\Models\User;

class ActivityLogPolicy
{
    /**
     * Determine whether the user can view activity logs.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('logs.view');
    }
}
