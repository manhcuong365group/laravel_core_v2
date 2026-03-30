<?php

namespace App\Actions\Category;

use App\Data\CategoryData;
use App\Models\Category;
use App\Services\Media\MediaService;

use Illuminate\Support\Facades\DB;

class CreateCategoryAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(CategoryData $data): Category
    {
        return DB::transaction(function () use ($data) {
            $category = Category::create($data->toArray());

            if ($data->image) {
                $this->mediaService->uploadSingle($category, $data->image, 'image');
            }

            return $category;
        });
    }
}
