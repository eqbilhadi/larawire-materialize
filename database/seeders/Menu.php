<?php

namespace Database\Seeders;

use App\Models\SysMenu as ModelsMenu;
use App\Models\SysRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Menu extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = SysRole::findByName('developer');

        /**
         * ------------------------------------------------------------------
         * Menu List
         * ------------------------------------------------------------------
         *
         * label_name_en : English
         * label_name_pt : Portuguese
         * label_name_tl : Tetun (dari 'Timor-Leste')
         *
         */
        $menus = [
            [
                'icon' => 'ri ri-dashboard-line',
                'label_name_en' => 'Dashboard',
                'label_name_pt' => 'Painel',
                'label_name_tl' => 'Painél',
                'controller_name' => 'app\Http\Controllers\DashboardController',
                'route_name' => 'dashboard',
                'url' => 'dashboard',
                'sort_num' => '1',
                'is_divider' => false
            ],
            [
                'icon' => 'ri ri-list-settings-fill',
                'label_name_en' => 'Access Settings',
                'label_name_pt' => 'Configurações de Acesso',
                'label_name_tl' => 'Definisaun Asesu',
                'controller_name' => null,
                'route_name' => 'rbac.index',
                'url' => 'rbac',
                'sort_num' => '2',
                'is_divider' => true
            ],
            [
                'icon' => 'ri ri-layout-horizontal-line',
                'label_name_en' => 'Navigation Management',
                'label_name_pt' => 'Gerenciamento de Navegação',
                'label_name_tl' => 'Jestaun Navegasaun',
                'controller_name' => 'app\Http\Controllers\Rbac\NavigationManagementController',
                'route_name' => 'rbac.nav.index',
                'url' => 'rbac/navigation-management',
                'sort_num' => '3',
                'is_divider' => false
            ],
            [
                'icon' => 'ri ri-key-2-line',
                'label_name_en' => 'Permission Management',
                'label_name_pt' => 'Gerenciamento de Permissões',
                'label_name_tl' => 'Jestaun Permisaun',
                'controller_name' => 'app\Http\Controllers\Rbac\PermissionManagementController',
                'route_name' => 'rbac.permission.index',
                'url' => 'rbac/permission-management',
                'sort_num' => '4',
                'is_divider' => false
            ],
            [
                'icon' => 'ri ri-shield-keyhole-line',
                'label_name_en' => 'Role Management',
                'label_name_pt' => 'Gerenciamento de Funções',
                'label_name_tl' => 'Jestaun Papél',
                'controller_name' => 'app\Http\Controllers\Rbac\RoleManagementController',
                'route_name' => 'rbac.role.index',
                'url' => 'rbac/role-management',
                'sort_num' => '5',
                'is_divider' => false
            ],
            [
                'icon' => 'ri ri-user-settings-line',
                'label_name_en' => 'User Management',
                'label_name_pt' => 'Gerenciamento de Usuários',
                'label_name_tl' => 'Jestaun Uzuáriu',
                'controller_name' => 'app\Http\Controllers\Rbac\UserManagementController',
                'route_name' => 'rbac.user.index',
                'url' => 'rbac/user-management',
                'sort_num' => '6',
                'is_divider' => false
            ]
        ];

        foreach ($menus as $menu) {
            $menuModel = ModelsMenu::updateOrCreate(
                [
                    'url' => $menu['url']
                ],
                [
                    'icon' => $menu['icon'],
                    'label_name_en' => $menu['label_name_en'],
                    'label_name_pt' => $menu['label_name_pt'],
                    'label_name_tl' => $menu['label_name_tl'],
                    'controller_name' => $menu['controller_name'],
                    'route_name' => $menu['route_name'],
                    'sort_num' => $menu['sort_num'],
                    'is_divider' => $menu['is_divider']
                ]
            );

            // Tetap lampirkan menu ini ke role 'developer'
            $developer->menus()->syncWithoutDetaching($menuModel->id);
        }
    }
}
