<?php

namespace App\Actions\Setting;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UpdateSocialSetting
{
    public function handle(array $data): void
    {
        foreach ($data as $key => $value) {
            if ($value instanceof UploadedFile) {
                $fileName = $key . '_' . time() . '.' . $value->getClientOriginalExtension();
                $path = $value->storeAs('settings', $fileName, 'public');
                $value = Storage::url($path);
            }

            Setting::set($key, $value, [
                'group' => 'social',
                'type' => is_array($value) ? 'json' : 'text',
            ]);
        }
    }
}
