<?php

namespace Database\Seeders;

use App\Models\SysMenu as ModelsMenu;
use App\Models\SysRole;
use Illuminate\Database\Seeder;
use Modules\Rbac\Models\SysMenuAction;

class Menu extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Changes from original:
     *  - Added `permission_prefix` to each menu that has actions
     *  - Removed static SysPermission usage — actions are now rows in sys_menu_actions
     *  - Actions are linked to the developer role via role_menu_action pivot
     */
    public function run(): void
    {
        $developer = SysRole::findByName('developer');

        /**
         * ------------------------------------------------------------------
         * Menu definitions
         * ------------------------------------------------------------------
         * permission_prefix : used to build permission names and action slugs
         *                     null = menu has no granular actions (e.g. Dashboard)
         *
         * actions : dynamic actions for this menu.
         *   - action       : slug, lowercase (used to build permission_name)
         *   - label        : human-readable button label
         *   - route_name   : named Laravel route this action maps to
         *   - order        : display order
         * ------------------------------------------------------------------
         */
        $menus = [
            [
                'icon'              => 'ri ri-dashboard-line',
                'label_name_en'     => 'Dashboard',
                'label_name_pt'     => 'Painel',
                'label_name_tl'     => 'Painél',
                'controller_name'   => 'app\Http\Controllers\DashboardController',
                'route_name'        => 'dashboard',
                'url'               => 'dashboard',
                'sort_num'          => 1,
                'is_divider'        => false,
                'permission_prefix' => null,    // No granular actions needed
                'actions'           => [],
            ],
            [
                'icon'              => 'ri ri-list-settings-fill',
                'label_name_en'     => 'Access Settings',
                'label_name_pt'     => 'Configurações de Acesso',
                'label_name_tl'     => 'Definisaun Asesu',
                'controller_name'   => null,
                'route_name'        => 'rbac.index',
                'url'               => 'rbac',
                'sort_num'          => 2,
                'is_divider'        => true,
                'permission_prefix' => null,    // Parent/divider — no actions
                'actions'           => [],
            ],
            [
                'icon'              => 'ri ri-layout-horizontal-line',
                'label_name_en'     => 'Navigation Management',
                'label_name_pt'     => 'Gerenciamento de Navegação',
                'label_name_tl'     => 'Jestaun Navegasaun',
                'controller_name'   => 'Modules\Rbac\Http\Controllers\NavManagementController',
                'route_name'        => 'rbac.nav.index',
                'url'               => 'rbac/navigation-management',
                'sort_num'          => 3,
                'is_divider'        => false,
                'permission_prefix' => 'nav',
                'actions'           => [
                    ['action' => 'create', 'label' => 'Add Navigation', 'route_name' => 'rbac.nav.create', 'order' => 1],
                    ['action' => 'edit', 'label' => 'Edit Navigation', 'route_name' => 'rbac.nav.edit', 'order' => 2],
                    ['action' => 'delete', 'label' => 'Delete Navigation', 'route_name' => 'rbac.nav.destroy', 'order' => 3],
                    ['action' => 'sort', 'label' => 'Sort Navigation', 'route_name' => 'rbac.nav.sort', 'order' => 4],
                ],
            ],
            [
                'icon'              => 'ri ri-key-2-line',
                'label_name_en'     => 'Permission Management',
                'label_name_pt'     => 'Gerenciamento de Permissões',
                'label_name_tl'     => 'Jestaun Permisaun',
                'controller_name'   => 'Modules\Rbac\Http\Controllers\PermissionManagementController',
                'route_name'        => 'rbac.permission.index',
                'url'               => 'rbac/permission-management',
                'sort_num'          => 4,
                'is_divider'        => false,
                'permission_prefix' => 'permission',
                'actions'           => [
                    ['action' => 'create', 'label' => 'Add Permission', 'route_name' => 'rbac.permission.store', 'order' => 1],
                    ['action' => 'edit', 'label' => 'Edit Permission', 'route_name' => 'rbac.permission.update', 'order' => 2],
                    ['action' => 'delete', 'label' => 'Delete Permission', 'route_name' => 'rbac.permission.destroy', 'order' => 3],
                ],
            ],
            [
                'icon'              => 'ri ri-shield-keyhole-line',
                'label_name_en'     => 'Role Management',
                'label_name_pt'     => 'Gerenciamento de Funções',
                'label_name_tl'     => 'Jestaun Papél',
                'controller_name'   => 'Modules\Rbac\Http\Controllers\RoleManagementController',
                'route_name'        => 'rbac.role.index',
                'url'               => 'rbac/role-management',
                'sort_num'          => 5,
                'is_divider'        => false,
                'permission_prefix' => 'role',
                'actions'           => [
                    ['action' => 'create', 'label' => 'Add Role', 'route_name' => 'rbac.role.create', 'order' => 1],
                    ['action' => 'edit', 'label' => 'Edit Role', 'route_name' => 'rbac.role.edit', 'order' => 2],
                    ['action' => 'delete', 'label' => 'Delete Role', 'route_name' => 'rbac.role.destroy',  'order' => 3],
                ],
            ],
            [
                'icon'              => 'ri ri-user-settings-line',
                'label_name_en'     => 'User Management',
                'label_name_pt'     => 'Gerenciamento de Usuários',
                'label_name_tl'     => 'Jestaun Uzuáriu',
                'controller_name'   => 'Modules\Rbac\Http\Controllers\UserManagementController',
                'route_name'        => 'rbac.user.index',
                'url'               => 'rbac/user-management',
                'sort_num'          => 6,
                'is_divider'        => false,
                'permission_prefix' => 'user',
                'actions'           => [
                    ['action' => 'create', 'label' => 'Add User', 'route_name' => 'rbac.user.create', 'order' => 1],
                    ['action' => 'edit', 'label' => 'Edit User', 'route_name' => 'rbac.user.edit', 'order' => 2],
                    ['action' => 'delete', 'label' => 'Delete User', 'route_name' => 'rbac.user.destroy', 'order' => 3],
                ],
            ],
        ];

        $permissions = [];
        foreach ($menus as $menuData) {
            $actions = $menuData['actions'];

            // ── 1. Upsert the menu row ────────────────────────────────────
            $menuModel = ModelsMenu::updateOrCreate(
                ['url' => $menuData['url']],
                [
                    'icon'              => $menuData['icon'],
                    'label_name_en'     => $menuData['label_name_en'],
                    'label_name_pt'     => $menuData['label_name_pt'],
                    'label_name_tl'     => $menuData['label_name_tl'],
                    'controller_name'   => $menuData['controller_name'],
                    'route_name'        => $menuData['route_name'],
                    'sort_num'          => $menuData['sort_num'],
                    'is_divider'        => $menuData['is_divider'],
                    'permission_prefix' => $menuData['permission_prefix'],
                ]
            );

            // ── 2. Link menu to developer role (same as before) ───────────
            $developer->menus()->syncWithoutDetaching($menuModel->id);

            // ── 3. Upsert SysMenuAction rows and link to developer ────────
            if (!empty($actions)) {
                $actionIds = [];
                foreach ($actions as $item) {
                    $permissionName = $menuData['permission_prefix'] . '.' . $item['action'];

                    // SysMenuAction::created() booted hook auto-creates
                    // the spatie permission when a new row is inserted
                    $menuAction = SysMenuAction::updateOrCreate(
                        [
                            'menu_id' => $menuModel->id,
                            'action' => $item['action'],
                        ],
                        [
                            'label' => $item['label'],
                            'permission_name' => $permissionName,
                            'route_name' => $item['route_name'],
                            'order' => $item['order'],
                        ]
                    );

                    $actionIds[] = $menuAction->id;
                    $permissions[] = $permissionName;
                }
            }
        }
        $developer->syncPermissions($permissions);
    }
}
