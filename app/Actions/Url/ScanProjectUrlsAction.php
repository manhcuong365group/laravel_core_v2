<?php

namespace App\Actions\Url;

use App\Models\Url;
use App\Models\Product;
use App\Models\Article;
use Illuminate\Support\Str;

class ScanProjectUrlsAction
{
    /**
     * Scan and import all project URLs into the shortener system.
     */
    public function execute(): array
    {
        $stats = [
            'total' => 0,
            'imported' => 0,
            'existing' => 0,
        ];

        // 1. Scan Articles & Pages
        $articles = Article::all();
        foreach ($articles as $article) {
            $stats['total']++;
            $urlPrefix = ($article->type === 'page' ? '/trang/' : '/bai-viet/');
            $originalUrl = url($urlPrefix . $article->slug);
            
            if ($this->importUrl($originalUrl, $article->title)) {
                $stats['imported']++;
            } else {
                $stats['existing']++;
            }
        }

        // 2. Scan Products
        $products = Product::all();
        foreach ($products as $product) {
            $stats['total']++;
            $originalUrl = url('/san-pham/' . $product->slug);
            
            if ($this->importUrl($originalUrl, $product->name)) {
                $stats['imported']++;
            } else {
                $stats['existing']++;
            }
        }

        return $stats;
    }

    protected function importUrl(string $originalUrl, string $title): bool
    {
        // Check if already exists in shortened URLs
        if (Url::where('original_url', $originalUrl)->exists()) {
            return false;
        }

        Url::create([
            'title' => $title,
            'original_url' => $originalUrl,
            'short_url' => Str::random(6),
            'description' => 'Imported from site scan.',
            'is_active' => true,
        ]);

        return true;
    }
}
