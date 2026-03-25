<?php

namespace App\Services\Media;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class MediaService
{
    /**
     * Handle single image upload for a model.
     */
    public function uploadSingle(Model $model, mixed $file, string $collection = 'image'): void
    {
        if ($file instanceof UploadedFile) {
            $model->clearMediaCollection($collection);
            $model->addMedia($file)->toMediaCollection($collection);
        }
    }

    /**
     * Handle multiple images upload for a model.
     */
    public function uploadMultiple(Model $model, array $files, string $collection = 'gallery'): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $model->addMedia($file)->toMediaCollection($collection);
            }
        }
    }
}
