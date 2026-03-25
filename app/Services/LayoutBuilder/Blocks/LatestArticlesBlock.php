<?php

namespace App\Services\LayoutBuilder\Blocks;

use App\Services\LayoutBuilder\BlockContract;

class LatestArticlesBlock implements BlockContract
{
    public static function type(): string
    {
        return 'latest_articles';
    }

    public static function label(): string
    {
        return 'Bài viết mới nhất';
    }

    public static function icon(): string
    {
        return 'ti-news';
    }

    public static function description(): string
    {
        return 'Hiển thị danh sách bài viết/tin tức mới nhất.';
    }

    public static function category(): string
    {
        return 'content';
    }

    public static function defaultData(): array
    {
        return [
            'title' => 'Tin tức mới nhất',
            'subtitle' => 'Cập nhật thông tin từ chúng tôi',
            'type' => 'post',
            'limit' => 6,
            'columns' => 3,
            'show_excerpt' => true,
            'show_date' => true,
        ];
    }

    public static function validationRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'type' => 'required|string|in:post,news',
            'limit' => 'required|integer|min:1|max:12',
            'columns' => 'required|integer|in:2,3,4',
            'show_excerpt' => 'boolean',
            'show_date' => 'boolean',
        ];
    }

    public static function viewName(): string
    {
        return 'themes.blocks.latest-articles';
    }

    public static function settingsViewName(): string
    {
        return 'backend.layout-builder.settings.latest-articles';
    }
}

