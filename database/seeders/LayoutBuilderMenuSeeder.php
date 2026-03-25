<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class LayoutBuilderMenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::firstOrCreate(
            ['route_name' => 'backend.layout-builder.index'],
            [
                'parent_id' => null,
                'label' => 'Layout Builder',
                'icon' => 'ti ti-layout-dashboard',
                'route_params' => null,
                'permission' => 'settings.edit',
                'order_column' => 35,
                'group_label' => 'Hệ thống',
                'is_active' => true,
            ]
        );
    }
}

