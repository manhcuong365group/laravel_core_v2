<?php

namespace App\Policies;

use App\Models\User;

class SettingPolicy
{
    /**
     * Determine whether the user can view settings.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('settings.view');
    }

    /**
     * Determine whether the user can update settings.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo('settings.edit');
    }

    /**
     * Determine whether the user can view SEO pages.
     */
    public function viewSeo(User $user): bool
    {
        return $user->hasPermissionTo('seo.view');
    }

    /**
     * Determine whether the user can update SEO pages.
     */
    public function updateSeo(User $user): bool
    {
        return $user->hasPermissionTo('seo.edit');
    }
}
