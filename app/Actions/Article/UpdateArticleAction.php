<?php

namespace App\Actions\Article;

use App\Data\ArticleData;
use App\Models\Article;
use App\Services\Media\MediaService;

class UpdateArticleAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(Article $article, ArticleData $data): Article
    {
        $payload = $data->toArray();

        if ($data->status === 'published' && empty($data->published_at)) {
            $payload['published_at'] = now();
        }

        $article->update($payload);

        $this->mediaService->uploadSingle($article, $data->featured_image, 'featured_image');

        return $article;
    }
}
