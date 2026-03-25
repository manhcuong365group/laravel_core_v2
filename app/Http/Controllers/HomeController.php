<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use App\Models\SeoPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index(): View
    {
        // Get SEO Config
        $seo = SeoPage::getForPage('home');

        // Get Featured Products
        $featuredProducts = Product::with(['category', 'brand'])
            ->active()
            ->featured()
            ->take(8)
            ->get();

        // Get Latest News
        $latestArticles = Article::with(['category', 'author'])
            ->published()
            ->ofType('post')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('theme::pages.home', [
            'seo' => $seo,
            'featuredProducts' => $featuredProducts,
            'latestArticles' => $latestArticles,
        ]);
    }
}
