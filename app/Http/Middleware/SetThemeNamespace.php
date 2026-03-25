<?php

namespace App\Http\Middleware;

use App\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetThemeNamespace
{
    public function __construct(protected TenantManager $tenantManager) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = $this->tenantManager->getTenant();
        $theme = $tenant ? $tenant->theme : 'default';

        // Register theme namespace
        $themePath = resource_path("views/themes/{$theme}");

        if (is_dir($themePath)) {
            View::addNamespace('theme', $themePath);
        } else {
            // Fallback to a default theme folder if it exists, otherwise use base views
            $defaultThemePath = resource_path('views/themes/default');
            if (is_dir($defaultThemePath)) {
                View::addNamespace('theme', $defaultThemePath);
            }
        }

        return $next($request);
    }
}
