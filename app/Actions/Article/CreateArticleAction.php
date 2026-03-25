<?php

namespace App\Actions\Article;

use App\Data\ArticleData;
use App\Models\Article;
use App\Services\Media\MediaService;
use Illuminate\Support\Facades\Auth;

class CreateArticleAction
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function execute(ArticleData $data): Article
    {
        $payload = $data->toArray();
        $payload['author_id'] = Auth::id();

        if ($data->status === 'published' && empty($data->published_at)) {
            $payload['published_at'] = now();
        }

        $article = Article::create($payload);

        $this->mediaService->uploadSingle($article, $data->featured_image, 'featured_image');

        return $article;
    }
}
