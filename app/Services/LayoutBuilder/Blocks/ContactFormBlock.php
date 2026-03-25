<?php

namespace App\Services\LayoutBuilder\Blocks;

use App\Services\LayoutBuilder\BlockContract;

class ContactFormBlock implements BlockContract
{
    public static function type(): string
    {
        return 'contact_form';
    }

    public static function label(): string
    {
        return 'Form liên hệ';
    }

    public static function icon(): string
    {
        return 'ti-mail';
    }

    public static function description(): string
    {
        return 'Hiển thị form liên hệ nhanh cho khách hàng.';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultData(): array
    {
        return [
            'title' => 'Liên hệ với chúng tôi',
            'subtitle' => 'Để lại thông tin, chúng tôi sẽ liên hệ bạn sớm nhất',
            'show_phone' => true,
            'show_address' => true,
            'bg_color' => '',
        ];
    }

    public static function validationRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'show_phone' => 'boolean',
            'show_address' => 'boolean',
            'bg_color' => 'nullable|string|max:20',
        ];
    }

    public static function viewName(): string
    {
        return 'themes.blocks.contact-form';
    }

    public static function settingsViewName(): string
    {
        return 'backend.layout-builder.settings.contact-form';
    }
}

