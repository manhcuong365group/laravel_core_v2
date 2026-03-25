<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\TenantManager::class, function ($app) {
            return new \App\Services\TenantManager();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(\App\Models\Subscriber::class, \App\Policies\NewsletterPolicy::class);
        Gate::policy(\App\Models\Contact::class, \App\Policies\ContactPolicy::class);
        Gate::policy(\App\Models\Url::class, \App\Policies\UrlPolicy::class);

        // Super-admin bypasses all permission checks
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Register View Composers
        \Illuminate\Support\Facades\View::composer('backend.layouts.partials.sidebar', \App\Http\View\Composers\BackendSidebarComposer::class);
    }
}

