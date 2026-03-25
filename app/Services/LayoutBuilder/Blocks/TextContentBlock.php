<?php

namespace App\Services\LayoutBuilder\Blocks;

use App\Services\LayoutBuilder\BlockContract;

class TextContentBlock implements BlockContract
{
    public static function type(): string
    {
        return 'text_content';
    }

    public static function label(): string
    {
        return 'Nội dung văn bản';
    }

    public static function icon(): string
    {
        return 'ti-align-left';
    }

    public static function description(): string
    {
        return 'Khối văn bản tự do với tiêu đề và nội dung HTML.';
    }

    public static function category(): string
    {
        return 'content';
    }

    public static function defaultData(): array
    {
        return [
            'title' => '',
            'content' => '<p>Nhập nội dung tại đây...</p>',
            'bg_color' => '',
            'text_align' => 'left',
            'max_width' => 'max-w-4xl',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|max:10000',
            'bg_color' => 'nullable|string|max:20',
            'text_align' => 'nullable|string|in:left,center,right',
            'max_width' => 'nullable|string|in:max-w-3xl,max-w-4xl,max-w-5xl,max-w-6xl,max-w-full',
        ];
    }

    public static function viewName(): string
    {
        return 'themes.blocks.text-content';
    }

    public static function settingsViewName(): string
    {
        return 'backend.layout-builder.settings.text-content';
    }
}

