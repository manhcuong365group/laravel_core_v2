<?php

namespace App\Services;

use App\Models\Tenant;

class TenantManager
{
    protected ?Tenant $tenant = null;

    public function setTenant(?Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function identifyTenant(string $domain): ?Tenant
    {
        $tenant = Tenant::where('domain', $domain)->first();

        if ($tenant && $tenant->is_active) {
            $this->setTenant($tenant);
            return $tenant;
        }

        return null;
    }
}
