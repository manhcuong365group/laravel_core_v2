<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LayoutBuilder\BlockRegistry;
use App\Services\LayoutBuilder\Blocks\HeroBlock;
use App\Services\LayoutBuilder\Blocks\FeaturedProductsBlock;
use App\Services\LayoutBuilder\Blocks\LatestArticlesBlock;
use App\Services\LayoutBuilder\Blocks\TextContentBlock;
use App\Services\LayoutBuilder\Blocks\ContactFormBlock;

class LayoutBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Đăng ký BlockRegistry dưới dạng Singleton
        // để đảm bảo chỉ có MỘT instance duy nhất trong toàn app.
        $this->app->singleton(BlockRegistry::class, function () {
            $registry = new BlockRegistry();

            $registry->registerMany([
                HeroBlock::class,
                FeaturedProductsBlock::class,
                LatestArticlesBlock::class,
                TextContentBlock::class,
                ContactFormBlock::class,
            ]);

            return $registry;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
