<?php

namespace App\Actions\Role;

use App\Data\RoleData;
use App\Events\RoleCreated;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Throwable;

class CreateRoleAction
{
    /**
     * @throws Throwable
     */
    public function execute(RoleData $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create($data->toArray());

            if (!empty($data->permissions)) {
                $role->syncPermissions($data->permissions);
            }

            event(new RoleCreated($role));

            return $role;
        });
    }
}
