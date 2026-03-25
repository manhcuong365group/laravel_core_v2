<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General settings
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Laravel Core CMS', 'type' => 'text', 'label' => 'Tên website', 'is_public' => true, 'order' => 1],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Hệ thống quản trị nội dung chuyên nghiệp', 'type' => 'text', 'label' => 'Tagline', 'is_public' => true, 'order' => 2],
            ['group' => 'general', 'key' => 'site_logo', 'value' => '', 'type' => 'image', 'label' => 'Logo', 'is_public' => true, 'order' => 3],
            ['group' => 'general', 'key' => 'site_favicon', 'value' => '', 'type' => 'image', 'label' => 'Favicon', 'is_public' => true, 'order' => 4],
            ['group' => 'general', 'key' => 'timezone', 'value' => 'Asia/Ho_Chi_Minh', 'type' => 'text', 'label' => 'Múi giờ', 'is_public' => false, 'order' => 5],
            ['group' => 'general', 'key' => 'date_format', 'value' => 'd/m/Y', 'type' => 'text', 'label' => 'Định dạng ngày', 'is_public' => false, 'order' => 6],
            ['group' => 'general', 'key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'label' => 'Chế độ bảo trì', 'is_public' => false, 'order' => 7],

            // Contact settings
            ['group' => 'contact', 'key' => 'company_name', 'value' => 'Công ty TNHH ABC', 'type' => 'text', 'label' => 'Tên công ty', 'is_public' => true, 'order' => 1],
            ['group' => 'contact', 'key' => 'address', 'value' => '123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh', 'type' => 'textarea', 'label' => 'Địa chỉ', 'is_public' => true, 'order' => 2],
            ['group' => 'contact', 'key' => 'phone', 'value' => '0901234567', 'type' => 'text', 'label' => 'Số điện thoại', 'is_public' => true, 'order' => 3],
            ['group' => 'contact', 'key' => 'hotline', 'value' => '1800 1234', 'type' => 'text', 'label' => 'Hotline', 'is_public' => true, 'order' => 4],
            ['group' => 'contact', 'key' => 'email', 'value' => 'contact@example.com', 'type' => 'text', 'label' => 'Email', 'is_public' => true, 'order' => 5],
            ['group' => 'contact', 'key' => 'working_hours', 'value' => 'T2 - T7: 8:00 - 17:30', 'type' => 'text', 'label' => 'Giờ làm việc', 'is_public' => true, 'order' => 6],
            ['group' => 'contact', 'key' => 'google_maps_embed', 'value' => '', 'type' => 'textarea', 'label' => 'Google Maps Embed', 'is_public' => true, 'order' => 7],
            ['group' => 'contact', 'key' => 'google_maps_link', 'value' => '', 'type' => 'text', 'label' => 'Google Maps Link', 'is_public' => true, 'order' => 8],

            // Social settings
            ['group' => 'social', 'key' => 'facebook', 'value' => '', 'type' => 'text', 'label' => 'Facebook', 'is_public' => true, 'order' => 1],
            ['group' => 'social', 'key' => 'youtube', 'value' => '', 'type' => 'text', 'label' => 'YouTube', 'is_public' => true, 'order' => 2],
            ['group' => 'social', 'key' => 'instagram', 'value' => '', 'type' => 'text', 'label' => 'Instagram', 'is_public' => true, 'order' => 3],
            ['group' => 'social', 'key' => 'twitter', 'value' => '', 'type' => 'text', 'label' => 'Twitter/X', 'is_public' => true, 'order' => 4],
            ['group' => 'social', 'key' => 'linkedin', 'value' => '', 'type' => 'text', 'label' => 'LinkedIn', 'is_public' => true, 'order' => 5],
            ['group' => 'social', 'key' => 'tiktok', 'value' => '', 'type' => 'text', 'label' => 'TikTok', 'is_public' => true, 'order' => 6],
            ['group' => 'social', 'key' => 'zalo', 'value' => '', 'type' => 'text', 'label' => 'Zalo', 'is_public' => true, 'order' => 7],

            // SEO settings
            ['group' => 'seo', 'key' => 'meta_title_default', 'value' => 'Laravel Core CMS', 'type' => 'text', 'label' => 'Meta Title mặc định', 'is_public' => false, 'order' => 1],
            ['group' => 'seo', 'key' => 'meta_description_default', 'value' => 'Hệ thống quản trị nội dung chuyên nghiệp', 'type' => 'textarea', 'label' => 'Meta Description mặc định', 'is_public' => false, 'order' => 2],
            ['group' => 'seo', 'key' => 'meta_keywords_default', 'value' => '', 'type' => 'text', 'label' => 'Meta Keywords mặc định', 'is_public' => false, 'order' => 3],
            ['group' => 'seo', 'key' => 'google_analytics', 'value' => '', 'type' => 'text', 'label' => 'Google Analytics ID', 'is_public' => false, 'order' => 4],
            ['group' => 'seo', 'key' => 'google_tag_manager', 'value' => '', 'type' => 'text', 'label' => 'Google Tag Manager ID', 'is_public' => false, 'order' => 5],
            ['group' => 'seo', 'key' => 'facebook_pixel', 'value' => '', 'type' => 'text', 'label' => 'Facebook Pixel ID', 'is_public' => false, 'order' => 6],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
