<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('backend_menus')->truncate();

        $menuConfig = config('backend.menu');

        foreach ($menuConfig as $groupIndex => $group) {
            $groupLabel = $group['group'] ?? null;
            $items = $group['items'] ?? [];

            foreach ($items as $itemIndex => $item) {
                try {
                    // Xử lý permission: có thể là mảng hoặc string
                    $permission = $item['permission'] ?? null;
                    if (is_array($permission)) {
                        $permission = implode(',', $permission);
                    }

                    // Xử lý route_params: đảm bảo là mảng hoặc null
                    $routeParams = $item['route_params'] ?? null;

                    $parentMenu = Menu::create([
                        'label'        => $item['label'],
                        'icon'         => $item['icon'] ?? null,
                        'route_name'   => $item['route'] ?? null,
                        'route_params' => $routeParams, // Model sẽ cast sang json
                        'permission'   => $permission, // Chuỗi phân cách dấu phẩy
                        'group_label'  => $groupLabel,
                        'order_column' => ($groupIndex * 100) + $itemIndex,
                        'is_active'    => true,
                    ]);

                    if (isset($item['submenu']) && is_array($item['submenu'])) {
                        foreach ($item['submenu'] as $subIndex => $subItem) {
                            $subPermission = $subItem['permission'] ?? null;
                            if (is_array($subPermission)) {
                                $subPermission = implode(',', $subPermission);
                            }

                            $subRouteParams = $subItem['route_params'] ?? null;

                            Menu::create([
                                'parent_id'    => $parentMenu->id,
                                'label'        => $subItem['label'],
                                'icon'         => $subItem['icon'] ?? null,
                                'route_name'   => $subItem['route'] ?? null,
                                'route_params' => $subRouteParams,
                                'permission'   => $subPermission,
                                'order_column' => $subIndex,
                                'is_active'    => true,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    $this->command->error("Lỗi khi seed item: " . ($item['label'] ?? 'Unknown'));
                    $this->command->error($e->getMessage());
                }
            }
        }
    }
}

