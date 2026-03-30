<?php

namespace App\Actions\Category;

use App\Data\CategoryData;
use App\Models\Category;
use App\Services\Media\MediaService;

use Illuminate\Support\Facades\DB;

class UpdateCategoryAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(Category $category, CategoryData $data): Category
    {
        return DB::transaction(function () use ($category, $data) {
            $category->update($data->toArray());

            if ($data->image) {
                $this->mediaService->uploadSingle($category, $data->image, 'image');
            }

            return $category;
        });
    }
}
