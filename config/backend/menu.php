<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Sidebar Menu Configuration
    |--------------------------------------------------------------------------
    */

    [
        'group' => 'Tổng quan',
        'items' => [
            [
                'label' => 'Bảng điều khiển',
                'icon' => 'ti ti-smart-home',
                'route' => 'backend.dashboard',
                'active_on' => ['admin', 'admin/dashboard*'],
            ],
        ],
    ],

    [
        'group' => 'Sản phẩm',
        'permission' => ['products.view', 'brands.view', 'categories.view'],
        'items' => [
            [
                'label' => 'Quản lý kho',
                'icon' => 'ti ti-package',
                'active_on' => ['admin/products*', 'admin/brands*', 'admin/categories/product*'],
                'submenu' => [
                    [
                        'label' => 'Danh sách sản phẩm',
                        'route' => 'backend.products.index',
                        'permission' => 'products.view',
                        'active_on' => ['admin/products*'],
                    ],
                    [
                        'label' => 'Danh mục',
                        'route' => 'backend.categories.index',
                        'route_params' => ['type' => 'product'],
                        'permission' => 'categories.view',
                        'active_on' => ['admin/categories/product*'],
                    ],
                    [
                        'label' => 'Thương hiệu',
                        'route' => 'backend.brands.index',
                        'permission' => 'brands.view',
                        'active_on' => ['admin/brands*'],
                    ],
                ],
            ],
        ],
    ],

    [
        'group' => 'Nội dung',
        'permission' => 'articles.view',
        'items' => [
            [
                'label' => 'Tin tức & Blog',
                'icon' => 'ti ti-news',
                'active_on' => ['admin/articles/post*', 'admin/categories/news*'],
                'submenu' => [
                    [
                        'label' => 'Tất cả bài viết',
                        'route' => 'backend.articles.index',
                        'route_params' => ['type' => 'post'],
                        'active_on' => ['admin/articles/post*'],
                    ],
                    [
                        'label' => 'Bộ sưu tập',
                        'route' => 'backend.articles.index',
                        'route_params' => ['type' => 'album'],
                        'active_on' => ['admin/articles/album*'],
                    ],
                    [
                        'label' => 'Danh mục tin',
                        'route' => 'backend.categories.index',
                        'route_params' => ['type' => 'news'],
                        'active_on' => ['admin/categories/news*'],
                    ],
                ],
            ],
            [
                'label' => 'Trang tĩnh',
                'icon' => 'ti ti-browser',
                'route' => 'backend.articles.index',
                'route_params' => ['type' => 'page'],
                'active_on' => ['admin/articles/page*'],
            ],
        ],
    ],

    [
        'group' => 'Bán hàng',
        'permission' => ['orders.view', 'contacts.view'],
        'items' => [
            [
                'label' => 'Đơn hàng',
                'icon' => 'ti ti-shopping-cart',
                'route' => 'backend.orders.index',
                'permission' => 'orders.view',
                'active_on' => ['admin/orders*'],
            ],
            [
                'label' => 'Liên hệ',
                'icon' => 'ti ti-mail',
                'route' => 'backend.contacts.index',
                'permission' => 'contacts.view',
                'active_on' => ['admin/contacts*'],
            ],
        ],
    ],

    [
        'group' => 'SEO & Marketing',
        'permission' => ['seo.view', 'settings.view', 'newsletters.view'],
        'items' => [
            [
                'label' => 'Tối ưu SEO',
                'icon' => 'ti ti-search',
                'route' => 'backend.settings.seo-pages',
                'permission' => 'seo.view',
                'active_on' => ['admin/settings/seo-pages*'],
            ],
            [
                'label' => 'Điều hướng (301)',
                'icon' => 'ti ti-arrows-right-left',
                'route' => 'backend.settings.redirects',
                'permission' => 'seo.view',
                'active_on' => ['admin/settings/redirects*'],
            ],
            [
                'label' => 'Mạng xã hội',
                'icon' => 'ti ti-share',
                'route' => 'backend.settings.social',
                'permission' => 'settings.view',
                'active_on' => ['admin/settings/social*'],
            ],
            [
                'label' => 'Newsletter',
                'icon' => 'ti ti-mail-forward',
                'route' => 'backend.newsletters.index',
                'permission' => 'newsletters.view',
                'active_on' => ['admin/newsletters*'],
            ],
        ],
    ],

    [
        'group' => 'Hệ thống',
        'permission' => ['users.view', 'settings.view', 'logs.view'],
        'items' => [
            [
                'label' => 'Quản trị viên',
                'icon' => 'ti ti-users-group',
                'route' => 'backend.users.index',
                'permission' => 'users.view',
                'active_on' => ['admin/users*'],
            ],
            [
                'label' => 'Cài đặt chung',
                'icon' => 'ti ti-settings-automation',
                'route' => 'backend.settings.general',
                'permission' => 'settings.view',
                'active_on' => ['admin/settings/general*'],
            ],
            [
                'label' => 'Nhật ký hệ thống',
                'icon' => 'ti ti-history',
                'route' => 'backend.logs.index',
                'permission' => 'logs.view',
                'active_on' => ['admin/logs*'],
            ],
            [
                'label' => 'Quản lý Menu',
                'icon' => 'ti ti-menu-2',
                'route' => 'backend.menus',
                'permission' => 'settings.view',
                'active_on' => ['admin/menus*'],
            ],
            [
                'label' => 'Layout Builder',
                'icon' => 'ti ti-layout-dashboard',
                'route' => 'backend.layout-builder.index',
                'permission' => 'settings.view',
                'active_on' => ['admin/layout-builder*'],
            ],
        ],
    ],
];

