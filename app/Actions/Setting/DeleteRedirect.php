<?php

namespace App\Actions\Setting;

use App\Models\Redirect;

class DeleteRedirect
{
    public function handle(Redirect $redirect): bool
    {
        return $redirect->delete();
    }
}
