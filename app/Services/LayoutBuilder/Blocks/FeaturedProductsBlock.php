<?php

namespace App\Services\LayoutBuilder\Blocks;

use App\Services\LayoutBuilder\BlockContract;

class FeaturedProductsBlock implements BlockContract
{
    public static function type(): string
    {
        return 'featured_products';
    }

    public static function label(): string
    {
        return 'Sản phẩm nổi bật';
    }

    public static function icon(): string
    {
        return 'ti-shopping-bag';
    }

    public static function description(): string
    {
        return 'Hiển thị danh sách sản phẩm nổi bật theo danh mục.';
    }

    public static function category(): string
    {
        return 'product';
    }

    public static function defaultData(): array
    {
        return [
            'title' => 'Sản phẩm nổi bật',
            'subtitle' => 'Những sản phẩm được yêu thích nhất',
            'category_id' => null,
            'limit' => 8,
            'columns' => 4,
            'show_price' => true,
            'show_badge' => true,
        ];
    }

    public static function validationRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'category_id' => 'nullable|integer|exists:categories,id',
            'limit' => 'required|integer|min:1|max:24',
            'columns' => 'required|integer|in:2,3,4,6',
            'show_price' => 'boolean',
            'show_badge' => 'boolean',
        ];
    }

    public static function viewName(): string
    {
        return 'themes.blocks.featured-products';
    }

    public static function settingsViewName(): string
    {
        return 'backend.layout-builder.settings.featured-products';
    }
}

