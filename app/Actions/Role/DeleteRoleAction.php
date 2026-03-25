<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class DeleteRoleAction
{
    public function execute(Role $role): bool
    {
        if ($role->users()->count() > 0) {
            return false;
        }

        return $role->delete();
    }
}
