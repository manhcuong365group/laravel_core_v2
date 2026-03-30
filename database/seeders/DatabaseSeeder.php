<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run role and permission seeder first
        $this->call([
            RolePermissionSeeder::class,
            SettingSeeder::class,
            SeoPageSeeder::class,
            AdminPhaseOneMenuSeeder::class,
            LayoutBuilderMenuSeeder::class,
            AttributeSeeder::class,
        ]);

        // Create default Tenant for localhost
        \App\Models\Tenant::firstOrCreate(
            ['domain' => 'localhost'],
            [
                'name' => 'Local Development',
                'theme' => 'default',
                'is_active' => true,
                'modules' => ['blog', 'page', 'user'], // Example modules
                'settings' => ['site_name' => 'Laravel Core Local'],
            ]
        );

        \App\Models\Tenant::firstOrCreate(
            ['domain' => 'laravel_core.test'],
            [
                'name' => 'Laragon Local',
                'theme' => 'default',
                'is_active' => true,
                'modules' => ['blog', 'page', 'user'],
                'settings' => ['site_name' => 'Laravel Core Laragon'],
            ]
        );

        // Create specific admin user as requested
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
            ]
        );

        // Ensure role exists before assigning (safety check, though RolePermissionSeeder should handle it)
        if (\Spatie\Permission\Models\Role::where('name', 'super-admin')->exists()) {
            $admin->assignRole('super-admin');
        }
    }
}
