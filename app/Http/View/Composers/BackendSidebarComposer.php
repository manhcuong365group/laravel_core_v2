<?php

namespace App\Http\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class BackendSidebarComposer
{
    public function compose(View $view): void
    {
        $menus = Menu::root()
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->ordered();
            }])
            ->where('is_active', true)
            ->ordered()
            ->get();

        // Ensure key phase-1 modules are always visible in sidebar.
        $requiredMenus = collect([
            [
                'label' => 'Đơn hàng',
                'icon' => 'ti ti-shopping-cart',
                'route_name' => 'backend.orders.index',
                'permission' => 'orders.view',
                'group_label' => 'Vận hành',
                'order_column' => 420,
            ],
            [
                'label' => 'Bản tin',
                'icon' => 'ti ti-mail',
                'route_name' => 'backend.newsletters.index',
                'permission' => 'newsletters.view',
                'group_label' => 'Vận hành',
                'order_column' => 421,
            ],
            [
                'label' => 'Liên hệ',
                'icon' => 'ti ti-inbox',
                'route_name' => 'backend.contacts.index',
                'permission' => 'contacts.view',
                'group_label' => 'Vận hành',
                'order_column' => 422,
            ],
            [
                'label' => 'Nhật ký hoạt động',
                'icon' => 'ti ti-clipboard-list',
                'route_name' => 'backend.logs.index',
                'permission' => 'logs.view',
                'group_label' => 'Hệ thống',
                'order_column' => 499,
            ],
            [
                'label' => 'URL rút gọn',
                'icon' => 'ti ti-link',
                'route_name' => 'backend.urls.index',
                'permission' => 'urls.view', // Note: Need to add this permission if using strict RBAC
                'group_label' => 'Vận hành',
                'order_column' => 423,
            ],
        ]);

        $requiredByRoute = $requiredMenus->keyBy('route_name');

        $existingRouteNames = $menus
            ->pluck('route_name')
            ->filter()
            ->values()
            ->all();

        $fallbackMenus = $requiredMenus
            ->reject(function (array $item) use ($existingRouteNames) {
                if (!\Route::has($item['route_name'])) {
                    return true;
                }

                return in_array($item['route_name'], $existingRouteNames, true);
            })
            ->map(function (array $item) {
                return (object) [
                    'label' => $item['label'],
                    'icon' => $item['icon'],
                    'route_name' => $item['route_name'],
                    'route_params' => null,
                    'permission' => $item['permission'],
                    'group_label' => $item['group_label'],
                    'order_column' => $item['order_column'],
                    'children' => collect(),
                ];
            });

        $allMenus = collect($menus->all())
            ->merge($fallbackMenus)
            ->map(function ($menu) use ($requiredByRoute) {
                $routeName = $menu->route_name ?? null;
                if (!$routeName || !$requiredByRoute->has($routeName)) {
                    return $menu;
                }

                $sync = $requiredByRoute->get($routeName);
                $menu->label = $sync['label'];
                $menu->icon = $sync['icon'];
                $menu->permission = $sync['permission'];
                $menu->group_label = $sync['group_label'];
                $menu->order_column = $sync['order_column'];

                return $menu;
            })
            ->sortBy('order_column')
            ->values();

        // Group menu by group label.
        $groupedMenus = [];
        foreach ($allMenus as $menu) {
            $group = $menu->group_label ?: 'Khác';
            if (!isset($groupedMenus[$group])) {
                $groupedMenus[$group] = [
                    'group' => $group,
                    'items' => [],
                ];
            }
            $groupedMenus[$group]['items'][] = $menu;
        }

        $view->with('sidebarMenus', $groupedMenus);
    }
}


