<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BulkDeleteUserAction
{
    /**
     * @param array<int> $ids
     */
    public function execute(array $ids): int
    {
        // Loại bỏ ID của user hiện tại
        $ids = array_filter($ids, fn($id) => $id !== Auth::id());
        
        if (empty($ids)) {
            return 0;
        }

        return User::query()->whereIn('id', $ids)->delete();
    }
}
