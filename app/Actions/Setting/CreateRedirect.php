<?php

namespace App\Actions\Setting;

use App\Models\Redirect;

class CreateRedirect
{
    public function handle(array $data): Redirect
    {
        return Redirect::create($data);
    }
}
