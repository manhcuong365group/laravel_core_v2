<?php

namespace App\Actions\Setting;

use App\Models\SeoPage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class UpdateSeoPage
{
    public function handle(string $pageType, array $data): void
    {
        $ogImage = $data['og_image_url'] ?? null;
        if (isset($data['og_image_file']) && $data['og_image_file'] instanceof UploadedFile) {
            $file = $data['og_image_file'];
            $fileName = 'seo_' . $pageType . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('seo', $fileName, 'public');
            $ogImage = Storage::url($path);
        }

        $updateData = [
            'title' => $data['title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'og_title' => $data['og_title'] ?? null,
            'og_description' => $data['og_description'] ?? null,
            'og_image' => $ogImage,
            'twitter_card' => $data['twitter_card'] ?? null,
            'schema_markup' => $data['schema_markup'] ?? null,
            'custom_head' => $data['custom_head'] ?? null,
            'custom_body' => $data['custom_body'] ?? null,
        ];

        SeoPage::updateOrCreate(
            ['page_type' => $pageType],
            $updateData
        );
    }
}
