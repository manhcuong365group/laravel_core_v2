<?php

namespace App\Data;

class RoleData extends BaseData
{
    public function __construct(
        public string $name,
        public array $permissions = [],
    ) {
    }

    /**
     * Chỉ trả về các trường thuộc DB table roles.
     * Loại bỏ permissions (xử lý riêng qua syncPermissions).
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        unset($data['permissions']);
        return $data;
    }
}
