<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // =============================================
        // PERMISSIONS
        // =============================================
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Products
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // Categories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Articles
            'articles.view',
            'articles.create',
            'articles.edit',
            'articles.delete',

            // Brands
            'brands.view',
            'brands.create',
            'brands.edit',
            'brands.delete',

            // Settings
            'settings.view',
            'settings.edit',

            // SEO
            'seo.view',
            'seo.edit',

            // Orders
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.status',

            // Newsletters
            'newsletters.view',
            'newsletters.create',
            'newsletters.edit',
            'newsletters.delete',
            'newsletters.export',

            // Contacts
            'contacts.view',
            'contacts.edit',
            'contacts.delete',

            // Logs
            'logs.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // =============================================
        // ROLES
        // =============================================

        // Super Admin — Full access, cannot be restricted
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        // Super-admin gets ALL permissions automatically via Gate::before in AuthServiceProvider

        // Admin — Full CRUD on all modules
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        // Editor — Manage content (articles, products, categories, brands) but no system settings
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions([
            'dashboard.view',
            'products.view',
            'products.create',
            'products.edit',
            'categories.view',
            'categories.create',
            'categories.edit',
            'articles.view',
            'articles.create',
            'articles.edit',
            'brands.view',
            'brands.create',
            'brands.edit',
            'orders.view',
            'orders.edit',
            'newsletters.view',
            'contacts.view',
        ]);

        // User — Default role, no admin access
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // =============================================
        // ASSIGN SUPER-ADMIN TO FIRST USER (if exists)
        // =============================================
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasAnyRole(['super-admin', 'admin'])) {
            $firstUser->assignRole('super-admin');
        }

        $this->command->info('✅ Roles & Permissions seeded successfully!');
        $this->command->table(
            ['Role', 'Permissions Count'],
            Role::withCount('permissions')->get()->map(fn($r) => [$r->name, $r->permissions_count])->toArray()
        );
    }
}
