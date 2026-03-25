<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class AdminPhaseOneMenuSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'label' => 'Orders',
                'icon' => 'ti ti-shopping-cart',
                'route_name' => 'backend.orders.index',
                'permission' => 'orders.view',
                'group_label' => 'Operations',
                'order_column' => 420,
            ],
            [
                'label' => 'Newsletters',
                'icon' => 'ti ti-mail',
                'route_name' => 'backend.newsletters.index',
                'permission' => 'newsletters.view',
                'group_label' => 'Operations',
                'order_column' => 421,
            ],
            [
                'label' => 'Contacts',
                'icon' => 'ti ti-inbox',
                'route_name' => 'backend.contacts.index',
                'permission' => 'contacts.view',
                'group_label' => 'Operations',
                'order_column' => 422,
            ],
            [
                'label' => 'Activity Logs',
                'icon' => 'ti ti-clipboard-list',
                'route_name' => 'backend.logs.index',
                'permission' => 'logs.view',
                'group_label' => 'System',
                'order_column' => 499,
            ],
        ];

        foreach ($items as $item) {
            $menu = Menu::query()
                ->whereNull('parent_id')
                ->where('route_name', $item['route_name'])
                ->first();

            if ($menu) {
                $menu->update([
                    'label' => $item['label'],
                    'icon' => $item['icon'],
                    'permission' => $item['permission'],
                    'group_label' => $item['group_label'],
                    'order_column' => $item['order_column'],
                    'is_active' => true,
                ]);
                continue;
            }

            Menu::query()->create([
                'parent_id' => null,
                'label' => $item['label'],
                'icon' => $item['icon'],
                'route_name' => $item['route_name'],
                'route_params' => null,
                'permission' => $item['permission'],
                'group_label' => $item['group_label'],
                'order_column' => $item['order_column'],
                'is_active' => true,
            ]);
        }
    }
}


