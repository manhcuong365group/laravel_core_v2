<?php

namespace App\Actions\Category;

use App\Data\CategoryData;
use App\Models\Category;
use App\Services\Media\MediaService;

class CreateCategoryAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(CategoryData $data): Category
    {
        $category = Category::create($data->toArray());

        $this->mediaService->uploadSingle($category, $data->image, 'image');

        return $category;
    }
}
