<?php

namespace App\Data;

use Illuminate\Http\Request;

class ArticleData extends BaseData
{
    public function __construct(
        public string $title,
        public ?string $slug = null,
        public ?string $excerpt = null,
        public ?string $content = null,
        public ?int $category_id = null,
        public string $type = 'post',
        public string $status = 'draft',
        public ?string $published_at = null,
        public bool $is_featured = false,
        public bool $allow_comments = true,
        public ?int $order = 0,
        public ?string $meta_title = null,
        public ?string $meta_description = null,
        public ?string $meta_keywords = null,
        public ?string $canonical_url = null,
        public mixed $featured_image = null,
    ) {
        if ($this->content) {
            // TODO: Install a proper Purifier (e.g. mews/purifier) and use clean($this->content) here.
            $this->content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $this->content);
        }
    }

    /**
     * Override toArray() to remove featured_image as it's not stored in articles table.
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        unset($data['featured_image']);
        return $data;
    }
}
