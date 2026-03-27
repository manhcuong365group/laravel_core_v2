<?php

namespace App\Traits;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait WithArticleForms
{
    public string $type = 'post';

    // Form fields
    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $content = '';
    public string $category_id = '';
    public string $status = 'draft';
    public string $published_at = '';
    public bool $is_featured = false;
    public bool $allow_comments = true;
    public string $order = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $meta_keywords = '';
    public string $canonical_url = '';
    public $featured_image;

    /**
     * Common rules for Articles.
     */
    protected function articleRules(int $ignoreId = null): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug' . ($ignoreId ? ",$ignoreId" : ''),
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'order' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'featured_image' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Get singular title for display.
     */
    public function getSingularTitle(): string
    {
        return match ($this->type) {
            'post' => 'Bài viết',
            'news' => 'Tin tức',
            'page' => 'Trang tĩnh',
            default => 'Nội dung',
        };
    }

    /**
     * Get categories for the select dropdown.
     */
    public function getCategories(): Collection
    {
        return Category::ofType($this->type === 'page' ? 'page' : 'news')
            ->active()
            ->orderBy('name')
            ->get();
    }

    /**
     * Auto-generate slug and meta title when title is updated.
     */
    public function updatedTitle(): void
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
        
        if (empty($this->meta_title)) {
            $this->meta_title = $this->title;
        }
    }
}
