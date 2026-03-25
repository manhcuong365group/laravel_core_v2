<?php

namespace App\Actions\User;

use App\Data\UserData;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction
{
    public function execute(User $user, UserData $data): User
    {
        $userData = $data->toArray();
        if (isset($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $user->update($userData);

        if (isset($data->roles)) {
            $user->syncRoles($data->roles);
        }

        $this->handleMedia($user, $data);

        return $user;
    }

    protected function handleMedia(User $user, UserData $data): void
    {
        if ($data->avatar instanceof UploadedFile) {
            $user->addMedia($data->avatar)
                ->toMediaCollection('avatar');
        }
    }
}
