<?php

namespace Database\Seeders;

use App\Models\SeoPage;
use Illuminate\Database\Seeder;

class SeoPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'page_type' => 'home',
                'title' => 'Trang chủ | Laravel Core CMS',
                'meta_description' => 'Chào mừng đến với Laravel Core CMS - Hệ thống quản trị nội dung chuyên nghiệp',
                'meta_keywords' => 'cms, laravel, quản trị nội dung',
                'og_title' => 'Trang chủ | Laravel Core CMS',
                'og_description' => 'Chào mừng đến với Laravel Core CMS',
                'twitter_card' => 'summary_large_image',
            ],
            [
                'page_type' => 'products',
                'title' => 'Sản phẩm | Laravel Core CMS',
                'meta_description' => 'Khám phá các sản phẩm chất lượng cao',
                'meta_keywords' => 'sản phẩm, mua sắm',
                'og_title' => 'Sản phẩm | Laravel Core CMS',
                'og_description' => 'Khám phá các sản phẩm chất lượng cao',
                'twitter_card' => 'summary_large_image',
            ],
            [
                'page_type' => 'articles',
                'title' => 'Tin tức | Laravel Core CMS',
                'meta_description' => 'Cập nhật tin tức và bài viết mới nhất',
                'meta_keywords' => 'tin tức, bài viết, blog',
                'og_title' => 'Tin tức | Laravel Core CMS',
                'og_description' => 'Cập nhật tin tức và bài viết mới nhất',
                'twitter_card' => 'summary_large_image',
            ],
            [
                'page_type' => 'contact',
                'title' => 'Liên hệ | Laravel Core CMS',
                'meta_description' => 'Liên hệ với chúng tôi để được hỗ trợ',
                'meta_keywords' => 'liên hệ, hỗ trợ',
                'og_title' => 'Liên hệ | Laravel Core CMS',
                'og_description' => 'Liên hệ với chúng tôi để được hỗ trợ',
                'twitter_card' => 'summary_large_image',
            ],
        ];

        foreach ($pages as $page) {
            SeoPage::updateOrCreate(
                ['page_type' => $page['page_type']],
                $page
            );
        }
    }
}
