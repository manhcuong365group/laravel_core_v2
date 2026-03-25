<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UrlRedirectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------

| Web Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Preview New Frontend
Route::get('/new-frontend', function () {
    return view('frontend.index');
});

// Products
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

// Articles / News
Route::get('/tin-tuc', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/bai-viet/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/trang/{slug}', [PageController::class, 'show'])->name('pages.show');


// Contact
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact.index');
Route::post('/lien-he', [ContactController::class, 'store'])->name('contact.store');

// Auth / Dashboard (Authenticated User)
Route::get('/dashboard', function () {
    return view('theme::pages.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// URL Redirector
Route::get('/l/{code}', UrlRedirectController::class)->name('url.redirect');

require __DIR__ . '/auth.php';
