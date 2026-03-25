<?php

namespace App\Actions\Setting;

use App\Models\Redirect;

class UpdateRedirect
{
    public function handle(Redirect $redirect, array $data): bool
    {
        return $redirect->update($data);
    }
}
