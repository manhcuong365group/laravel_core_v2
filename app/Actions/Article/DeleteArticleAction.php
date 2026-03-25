<?php

namespace App\Actions\Article;

use App\Models\Article;

class DeleteArticleAction
{
    public function execute(int $id): bool
    {
        $article = Article::findOrFail($id);

        return (bool) $article->delete();
    }
}
