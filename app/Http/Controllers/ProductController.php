<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SeoPage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): View
    {
        $seo = SeoPage::getForPage('products');

        $query = Product::with(['category', 'brand'])
            ->active();

        // Filter by Category
        if ($request->has('category')) {
            $category = Category::where('slug', $request->get('category'))
                ->where('type', 'product')
                ->first();

            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter by Brand
        if ($request->has('brand')) {
            $brand = Brand::where('slug', $request->get('brand'))->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default: // newest
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::ofType('product')->roots()->active()->orderBy('order')->get();
        $brands = Brand::active()->orderBy('name')->get();

        return view('theme::pages.products.index', [
            'seo' => $seo,
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['category', 'brand', 'media'])
            ->firstOrFail();

        // Increment view count (optional logic could be added here)

        // Relate Products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->take(4)
            ->get();

        return view('theme::pages.products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
