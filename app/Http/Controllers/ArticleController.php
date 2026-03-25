<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\SeoPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles (News/Blog).
     */
    public function index(Request $request): View
    {
        $seo = SeoPage::getForPage('articles');

        $query = Article::with(['category', 'author'])
            ->published()
            ->ofType('post');

        if ($request->has('category')) {
            $category = Category::where('slug', $request->get('category'))
                ->where('type', 'news')
                ->first();

            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $articles = $query->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::ofType('news')->active()->orderBy('order')->get();
        $featuredArticles = Article::published()->ofType('post')->featured()->take(5)->get();

        return view('theme::pages.articles.index', [
            'seo' => $seo,
            'articles' => $articles,
            'categories' => $categories,
            'featuredArticles' => $featuredArticles,
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(string $slug): View
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->with(['category', 'author', 'tags'])
            ->firstOrFail();

        // Increase view count
        $article->increment('view_count');

        // Related Articles
        $relatedArticles = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->published()
            ->take(3)
            ->get();

        return view('theme::pages.articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }

    /**
     * Display static page (About, Terms, etc.)
     */
    public function page(string $slug): View
    {
        $page = Article::where('slug', $slug)
            ->published()
            ->ofType('page')
            ->firstOrFail();

        return view('theme::pages.show', [
            'page' => $page,
        ]);
    }
}
