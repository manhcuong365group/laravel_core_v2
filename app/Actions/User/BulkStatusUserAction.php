<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BulkStatusUserAction
{
    /**
     * @param array<int> $ids
     * @param bool $isActive
     */
    public function execute(array $ids, bool $isActive): int
    {
        // Loại bỏ ID của user hiện tại
        $ids = array_filter($ids, fn($id) => $id !== Auth::id());
        
        if (empty($ids)) {
            return 0;
        }

        return User::query()->whereIn('id', $ids)->update(['is_active' => $isActive]);
    }
}
