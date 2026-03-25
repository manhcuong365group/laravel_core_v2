<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TenantManager;

class IdentifyTenant
{
    protected TenantManager $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $domain = $request->getHost();
        $tenant = $this->tenantManager->identifyTenant($domain);

        if (!$tenant) {
            // For local development, fallback to localhost if no tenant found
            // Or abort(404); depending on strictness.
            // Let's assume strictness for production but allow localhost for dev if configured.

            // For now, let's just abort if strict mode is implied, or maybe not abort yet
            // to allow non-tenant routes (e.g. system admin).
            // But user asked for "nhieu module cho nhieu ten mien", so likely we need tenant context.

            // Let's just continue without tenant, or maybe abort if it's a tenant route.
            // For simplicity, let's log it and continue, but in a real app might redirect or 404.
            // abort(404, 'Tenant not found');
        }

        return $next($request);
    }
}
