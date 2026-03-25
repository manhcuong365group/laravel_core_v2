<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Livewire\Backend\Articles\IndexPage as ArticleIndexPage;
use App\Livewire\Backend\Articles\CreatePage as ArticleCreatePage;
use App\Livewire\Backend\Articles\EditPage as ArticleEditPage;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\EditorController;
use App\Http\Controllers\Backend\OrderController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group.
|
*/

Route::prefix('admin')->name('backend.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', \App\Livewire\Backend\Dashboard\IndexPage::class)->name('dashboard');
    Route::get('/dashboard', \App\Livewire\Backend\Dashboard\IndexPage::class)->name('dashboard.index');

    // ===========================================
    // CATEGORIES (Dynamic Type)
    // ===========================================
    Route::prefix('categories/{type?}')->name('categories.')->middleware('permission:categories.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Categories\IndexPage::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Categories\CreatePage::class)->name('create')->middleware('permission:categories.create');
        Route::get('/{category}/edit', \App\Livewire\Backend\Categories\EditPage::class)->name('edit')->middleware('permission:categories.edit');
    });

    // ===========================================
    // PRODUCTS
    // ===========================================
    Route::prefix('products')->name('products.')->middleware('permission:products.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Products\IndexPage::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Products\CreatePage::class)->name('create')->middleware('permission:products.create');
        Route::post('/{product}/copy', [ProductController::class, 'copy'])->name('copy')->middleware('permission:products.create');
        Route::get('/{product}/edit', \App\Livewire\Backend\Products\EditPage::class)->name('edit')->middleware('permission:products.edit');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy')->middleware('permission:products.delete');
    });

    // ===========================================
    // BRANDS
    // ===========================================
    Route::prefix('brands')->name('brands.')->middleware('permission:brands.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Brands\IndexPage::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Brands\CreatePage::class)->name('create')->middleware('permission:brands.create');
        Route::get('/{brand}/edit', \App\Livewire\Backend\Brands\EditPage::class)->name('edit')->middleware('permission:brands.edit');
    });

    // ===========================================
    // ARTICLES (News, Posts, Pages)
    // ===========================================
    Route::prefix('articles/{type}')->name('articles.')->middleware('permission:articles.view')->group(function () {
        Route::get('/', ArticleIndexPage::class)->name('index');
        Route::get('/create', ArticleCreatePage::class)->name('create')->middleware('permission:articles.create');
        Route::get('/{article}/edit', ArticleEditPage::class)->name('edit')->middleware('permission:articles.edit');
    });

    // ===========================================
    // URL SHORTENER
    // ===========================================
    Route::prefix('urls')->name('urls.')->group(function () {
        Route::get('/', \App\Livewire\Backend\Urls\IndexPage::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Urls\CreatePage::class)->name('create');
        Route::get('/{url}/edit', \App\Livewire\Backend\Urls\EditPage::class)->name('edit');
    });

    // ===========================================
    // USERS & ROLES (restricted to user management permissions)
    // ===========================================
    Route::prefix('users')->name('users.')->middleware('permission:users.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Users\IndexPage::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Users\CreatePage::class)->name('create')->middleware('permission:users.create');
        Route::get('/{user}/edit', \App\Livewire\Backend\Users\EditPage::class)->name('edit')->middleware('permission:users.edit');

        // Roles (restricted to roles permissions)
        Route::get('/roles', \App\Livewire\Backend\Roles\IndexPage::class)->name('roles')->middleware('permission:roles.view');
        Route::get('/roles/create', \App\Livewire\Backend\Roles\CreatePage::class)->name('roles.create')->middleware('permission:roles.create');
        Route::get('/roles/{role}/edit', \App\Livewire\Backend\Roles\EditPage::class)->name('roles.edit')->middleware('permission:roles.edit');
    });

    // ===========================================
    // SETTINGS (restricted to settings permissions)
    // ===========================================
    Route::prefix('settings')->name('settings.')->middleware('permission:settings.view')->group(function () {
        // General settings
        Route::get('/', \App\Livewire\Backend\Settings\GeneralPage::class)->name('index');
        Route::get('/general', \App\Livewire\Backend\Settings\GeneralPage::class)->name('general');

        // Contact settings
        Route::get('/contact', \App\Livewire\Backend\Settings\ContactPage::class)->name('contact');

        // Social settings
        Route::get('/social', \App\Livewire\Backend\Settings\SocialPage::class)->name('social');

        // SEO Pages
        Route::get('/seo-pages', \App\Livewire\Backend\Settings\SeoPagesIndex::class)->name('seo-pages')->middleware('permission:seo.view');
        Route::get('/seo-pages/{pageType}', \App\Livewire\Backend\Settings\SeoPagesEdit::class)->name('seo-pages.edit')->middleware('permission:seo.edit');

        // Redirects
        Route::get('/redirects', \App\Livewire\Backend\Settings\RedirectsPage::class)->name('redirects')->middleware('permission:seo.view');
    });

    // Menu Manager
    Route::get('/menus', \App\Livewire\Backend\MenuManager::class)
        ->name('menus')
        ->middleware('permission:settings.view');

    // ===========================================
    // ORDERS
    // ===========================================
    Route::prefix('orders')->name('orders.')->middleware('permission:orders.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Orders\IndexPage::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Orders\CreatePage::class)->name('create')->middleware('permission:orders.create');
        Route::get('/{order}', \App\Livewire\Backend\Orders\ShowPage::class)->name('show');
        Route::get('/{order}/edit', \App\Livewire\Backend\Orders\EditPage::class)->name('edit')->middleware('permission:orders.edit');
    });

    // ===========================================
    // NEWSLETTERS
    // ===========================================
    Route::prefix('newsletters')->name('newsletters.')->middleware('permission:newsletters.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Newsletters\IndexPage::class)->name('index');
    });

    // ===========================================
    // CONTACT INBOX
    // ===========================================
    Route::prefix('contacts')->name('contacts.')->middleware('permission:contacts.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Contacts\IndexPage::class)->name('index');
        Route::get('/{contact}', \App\Livewire\Backend\Contacts\ShowPage::class)->name('show');
    });

    // ===========================================
    // ACTIVITY LOGS
    // ===========================================
    Route::prefix('logs')->name('logs.')->middleware('permission:logs.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Logs\IndexPage::class)->name('index');
    });

    // ===========================================
    // LAYOUT BUILDER (Page Builder)
    // ===========================================
    Route::prefix('layout-builder')->name('layout-builder.')->middleware('permission:settings.edit')->group(function () {
        Route::get('/', \App\Livewire\Backend\LayoutBuilder::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\LayoutBuilder::class)->name('create');
        Route::get('/{pageId}/edit', \App\Livewire\Backend\LayoutBuilder::class)->name('edit');
    });

    // ===========================================
    // PROFILE & SECURITY
    // ===========================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', \App\Livewire\Backend\Profile\ProfilePage::class)->name('index');
        Route::get('/security', \App\Livewire\Backend\Profile\SecurityPage::class)->name('security');
    });

    // Editor Upload
    Route::post('/editor/upload', [EditorController::class, 'upload'])->name('editor.upload');

    // ===========================================
    // URL SHORTENER
    // ===========================================
    Route::prefix('urls')->name('urls.')->middleware('permission:urls.view')->group(function () {
        Route::get('/', \App\Livewire\Backend\Urls\IndexPage::class)->name('index');
        Route::get('/create', \App\Livewire\Backend\Urls\CreatePage::class)->name('create')->middleware('permission:urls.create');
        Route::get('/{url}/edit', \App\Livewire\Backend\Urls\EditPage::class)->name('edit')->middleware('permission:urls.edit');
    });
});


