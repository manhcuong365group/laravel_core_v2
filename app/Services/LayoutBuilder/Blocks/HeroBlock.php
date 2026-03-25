<?php

namespace App\Services\LayoutBuilder\Blocks;

use App\Services\LayoutBuilder\BlockContract;

class HeroBlock implements BlockContract
{
    public static function type(): string
    {
        return 'hero';
    }

    public static function label(): string
    {
        return 'Hero Banner';
    }

    public static function icon(): string
    {
        return 'ti-photo';
    }

    public static function description(): string
    {
        return 'Banner chính với tiêu đề, mô tả và nút CTA nổi bật.';
    }

    public static function category(): string
    {
        return 'layout';
    }

    public static function defaultData(): array
    {
        return [
            'title' => 'Chào mừng đến với website',
            'subtitle' => 'Mô tả ngắn gọn về doanh nghiệp của bạn',
            'button_text' => 'Khám phá ngay',
            'button_url' => '#',
            'bg_color' => '#1e293b',
            'text_color' => '#ffffff',
            'image_url' => '',
            'overlay_opacity' => 50,
        ];
    }

    public static function validationRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_url' => 'nullable|string|max:500',
            'bg_color' => 'nullable|string|max:20',
            'text_color' => 'nullable|string|max:20',
            'image_url' => 'nullable|string|max:500',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
        ];
    }

    public static function viewName(): string
    {
        return 'themes.blocks.hero';
    }

    public static function settingsViewName(): string
    {
        return 'backend.layout-builder.settings.hero';
    }
}

