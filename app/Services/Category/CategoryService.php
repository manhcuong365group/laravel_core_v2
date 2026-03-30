<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Data\CategoryData;
use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\BulkDeleteCategoryAction;
use App\Actions\Category\BulkStatusCategoryAction;

class CategoryService
{
    public function __construct(
        protected CreateCategoryAction $createAction,
        protected UpdateCategoryAction $updateAction,
        protected DeleteCategoryAction $deleteAction,
        protected BulkDeleteCategoryAction $bulkDeleteAction,
        protected BulkStatusCategoryAction $bulkStatusAction
    ) {}

    /**
     * Create a new category.
     */
    public function create(CategoryData $data): Category
    {
        return $this->createAction->execute($data);
    }

    /**
     * Update an existing category.
     */
    public function update(Category $category, CategoryData $data): Category
    {
        return $this->updateAction->execute($category, $data);
    }

    /**
     * Delete a category.
     */
    public function delete(Category $category): void
    {
        $this->deleteAction->execute($category);
    }

    /**
     * Bulk delete categories by ids.
     */
    public function bulkDelete(array $ids): void
    {
        $this->bulkDeleteAction->execute($ids);
    }

    /**
     * Bulk update status for categories.
     */
    public function bulkStatus(array $ids, bool $isActive): void
    {
        $this->bulkStatusAction->execute($ids, $isActive);
    }

    /**
     * Reorder categories.
     */
    public function reorder(array $orders): void
    {
        foreach ($orders as $item) {
            Category::where('id', $item['id'])->update(['order' => $item['order']]);
        }
    }
}
