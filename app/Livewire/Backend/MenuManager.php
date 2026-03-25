<?php

namespace App\Livewire\Backend;

use App\Models\Menu;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Http\Controllers\Backend\MenuController;

class MenuManager extends Component
{
    public $menus;

    // Modal state
    public $showModal = false;
    public $isEdit = false;
    public $editingId = null;

    // Form fields
    public $label;
    public $icon;
    public $route_name;
    public $route_params;
    public $permission;
    public $parent_id;
    public $group_label;
    public $is_active = true;

    // Data lists
    public $adminRoutes = [];
    public $permissions = [];
    public $parentOptions = [];

    protected $listeners = ['updateOrder'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Load menus with children, sorted
        $rawMenus = Menu::root()->with('children')->get();

        $groupedMenus = [];
        foreach ($rawMenus as $menu) {
            $group = $menu->group_label ?: 'Khác';
            if (!isset($groupedMenus[$group])) {
                $groupedMenus[$group] = [
                    'group' => $group,
                    'items' => []
                ];
            }
            $groupedMenus[$group]['items'][] = $menu;
        }
        $this->menus = array_values($groupedMenus);

        // Load parent options for dropdown (flattened or just roots)
        $this->parentOptions = Menu::root()->pluck('label', 'id')->toArray();

        // Load admin routes dynamically
        $adminRoutes = [];
        $routes = \Illuminate\Support\Facades\Route::getRoutes()->getRoutes();

        foreach ($routes as $route) {
            $name = $route->getName();

            // Lọc route admin, method GET
            if ($name && \Illuminate\Support\Str::startsWith($name, 'backend.') && in_array('GET', $route->methods())) {
                // Bỏ qua các route con
                if (\Illuminate\Support\Str::contains($name, ['create', 'edit', 'store', 'update', 'destroy', 'delete', 'logout', 'media'])) {
                    continue;
                }

                $adminRoutes[] = [
                    'name' => $name,
                    'uri' => $route->uri(),
                ];
            }
        }

        usort($adminRoutes, fn($a, $b) => strcmp($a['name'], $b['name']));
        $this->adminRoutes = $adminRoutes;

        // Load permissions (Spatie)
        $this->permissions = \Spatie\Permission\Models\Permission::pluck('name', 'name')->toArray();
    }

    public function render()
    {
        return view('livewire.backend.menu-manager')
            ->layout('backend.layouts.app', ['title' => 'Quản lý Menu']);
    }

    // =========================================================================
    // CRUD ACTIONS
    // =========================================================================

    public function create()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $this->editingId = $id;
        $this->label = $menu->label;
        $this->icon = $menu->icon;
        $this->route_name = $menu->route_name;
        $this->route_params = $menu->route_params ? json_encode($menu->route_params) : null;
        $this->permission = $menu->permission;
        $this->parent_id = $menu->parent_id;
        $this->group_label = $menu->group_label; // Only for root
        $this->is_active = $menu->is_active;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'label' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'route_name' => 'nullable|string|max:255',
            'route_params' => 'nullable|json',
            'parent_id' => 'nullable|exists:backend_menus,id',
        ]);

        $data = [
            'label' => $this->label,
            'icon' => $this->icon,
            'route_name' => $this->route_name,
            'route_params' => $this->route_params ? json_decode($this->route_params, true) : null,
            'permission' => $this->permission,
            'parent_id' => $this->parent_id,
            'group_label' => $this->parent_id ? null : $this->group_label, // Submenu no group label
            'is_active' => $this->is_active,
        ];

        if ($this->isEdit) {
            Menu::where('id', $this->editingId)->update($data);
            $this->dispatch('toast', message: 'Cập nhật menu thành công!', type: 'success');
        } else {
            // New menu gets highest order + 1
            $maxOrder = Menu::where('parent_id', $this->parent_id)->max('order_column') ?? 0;
            $data['order_column'] = $maxOrder + 1;
            Menu::create($data);
            $this->dispatch('toast', message: 'Tạo menu mới thành công!', type: 'success');
        }

        $this->showModal = false;
        $this->loadData(); // Refresh list
    }

    public function delete($id)
    {
        $menu = Menu::findOrFail($id);

        // Check if menu has children
        if ($menu->children()->count() > 0) {
            $this->dispatch('toast', message: 'Không thể xóa menu cha đang có menu con!', type: 'error');
            return;
        }

        $menu->delete();
        $this->loadData();
        $this->dispatch('toast', message: 'Đã xóa menu.', type: 'success');
    }

    public function resetForm()
    {
        $this->reset(['label', 'icon', 'route_name', 'route_params', 'permission', 'parent_id', 'group_label', 'is_active', 'editingId']);
    }

    // =========================================================================
    // SORTING LOGIC
    // =========================================================================

    public function updateOrder($list)
    {
        foreach ($list as $item) {
            // Update root item
            Menu::where('id', $item['value'])->update([
                'order_column' => $item['order'],
                'parent_id' => null, // Crucial: root items must have no parent
                'group_label' => ($item['group'] ?? 'Khác') === 'Khác' ? null : $item['group']
            ]);

            // If it has children
            if (isset($item['items']) && count($item['items']) > 0) {
                foreach ($item['items'] as $subItem) {
                    Menu::where('id', $subItem['value'])->update([
                        'order_column' => $subItem['order'],
                        'parent_id' => $item['value'] // Set parent to root item
                    ]);
                }
            }
        }

        $this->dispatch('toast', message: 'Đã cập nhật thứ tự!', type: 'success');
        $this->loadData();
    }
}




