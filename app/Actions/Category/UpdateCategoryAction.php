<?php

namespace App\Actions\Category;

use App\Data\CategoryData;
use App\Models\Category;
use App\Services\Media\MediaService;

class UpdateCategoryAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(Category $category, CategoryData $data): Category
    {
        $category->update($data->toArray());

        $this->mediaService->uploadSingle($category, $data->image, 'image');

        return $category;
    }
}
