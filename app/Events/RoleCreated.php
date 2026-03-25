<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\Permission\Models\Role;

class RoleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Role $role)
    {
    }
}
