<?php

namespace App\Services\LayoutBuilder;

/**
 * Contract (Interface) mà mọi Block phải tuân thủ.
 * Đảm bảo tính nhất quán và an toàn cho hệ thống.
 */
interface BlockContract
{
    /**
     * Mã định danh duy nhất của block (ví dụ: 'hero', 'featured_products').
     */
    public static function type(): string;

    /**
     * Tên hiển thị trong Admin UI.
     */
    public static function label(): string;

    /**
     * Icon Tabler hiển thị trong Gallery chọn block.
     */
    public static function icon(): string;

    /**
     * Mô tả ngắn gọn cho admin.
     */
    public static function description(): string;

    /**
     * Danh mục phân loại block (layout, content, product, marketing).
     */
    public static function category(): string;

    /**
     * Cấu hình mặc định khi tạo block mới.
     */
    public static function defaultData(): array;

    /**
     * Quy tắc validation cho dữ liệu block.
     */
    public static function validationRules(): array;

    /**
     * Tên Blade view dùng để render block trên frontend.
     */
    public static function viewName(): string;

    /**
     * Tên Blade view dùng để render form cấu hình trong Admin.
     */
    public static function settingsViewName(): string;
}
