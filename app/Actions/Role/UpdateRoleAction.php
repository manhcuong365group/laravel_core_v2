<?php

namespace App\Actions\Role;

use App\Data\RoleData;
use App\Events\RoleUpdated;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Throwable;

class UpdateRoleAction
{
    /**
     * @throws Throwable
     */
    public function execute(Role $role, RoleData $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $role->update($data->toArray());

            if (isset($data->permissions)) {
                $role->syncPermissions($data->permissions);
            }

            event(new RoleUpdated($role));

            return $role;
        });
    }
}
