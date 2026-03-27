<?php

namespace App\Data;

use Illuminate\Http\Request;

class UserData extends BaseData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public array $roles = [],
        public bool $is_active = true,
        public mixed $avatar = null,
    ) {
    }

    /**
     * Override toArray() to remove password and avatar.
     * Roles are handled separately in User actions.
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        unset($data['password'], $data['avatar'], $data['roles']);
        return $data;
    }
}
