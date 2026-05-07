<?php

namespace Modules\Rbac\Database\Seeders;

use App\Models\SysMenu;
use App\Models\SysRole;
use Illuminate\Database\Seeder;
use Modules\Rbac\Models\SysMenuAction;
use Spatie\Permission\Models\Permission;

class RbacDatabaseSeeder extends Seeder
{
    /**
     * Example seeder showing how to create menus with dynamic actions
     * and assign them to roles.
     *
     * Run with: php artisan db:seed --class=Modules\\Rbac\\Database\\Seeders\\RbacDatabaseSeeder
     */
    public function run(): void
    {
        // ── 1. Create menus with permission_prefix ────────────────────────
        $menuGuru = SysMenu::updateOrCreate(
            ['route_name' => 'guru.index'],
            [
                'label_name_en'     => 'Teacher',
                'label_name_pt'     => 'Professor',
                'label_name_tl'     => 'Mestre',
                'icon'              => 'ri-user-settings-line',
                'url'               => '/guru',
                'permission_prefix' => 'guru',
                'is_active'         => true,
                'is_divider'        => false,
                'sort_num'          => 1,
            ]
        );

        $menuSiswa = SysMenu::updateOrCreate(
            ['route_name' => 'siswa.index'],
            [
                'label_name_en'     => 'Student',
                'label_name_pt'     => 'Estudante',
                'label_name_tl'     => 'Estudante',
                'icon'              => 'ri-group-line',
                'url'               => '/siswa',
                'permission_prefix' => 'siswa',
                'is_active'         => true,
                'is_divider'        => false,
                'sort_num'          => 2,
            ]
        );

        // ── 2. Create dynamic actions per menu ────────────────────────────
        // SysMenuAction::created() booted() hook auto-creates spatie permissions

        $this->seedActions($menuGuru, [
            ['action' => 'create', 'label' => 'Add Teacher',    'order' => 0],
            ['action' => 'edit',   'label' => 'Edit Teacher',   'order' => 1],
            ['action' => 'delete', 'label' => 'Delete Teacher', 'order' => 2],
            ['action' => 'export', 'label' => 'Export Excel',   'order' => 3],
        ]);

        $this->seedActions($menuSiswa, [
            ['action' => 'create', 'label' => 'Add Student',    'order' => 0],
            ['action' => 'edit',   'label' => 'Edit Student',   'order' => 1],
            ['action' => 'delete', 'label' => 'Delete Student', 'order' => 2],
        ]);

        // ── 3. Create roles and assign menu actions ───────────────────────
        $admin    = SysRole::firstOrCreate(['name' => 'admin']);
        $operator = SysRole::firstOrCreate(['name' => 'operator']);

        // Admin — all permissions
        $admin->syncPermissions(Permission::all());

        // Also sync all menu actions to admin via pivot
        $admin->menuActions()->sync(SysMenuAction::pluck('id')->toArray());
        $admin->menus()->sync(SysMenu::pluck('id')->toArray());

        // Operator — Teacher: view+create only; Student: view only
        $operatorActions = SysMenuAction::whereIn('permission_name', [
            'guru.create',
            'siswa.create',
        ])->pluck('id')->toArray();

        $operatorPermissions = array_merge(
            ['guru.access', 'guru.create', 'siswa.access', 'siswa.create'],
        );

        $operator->syncPermissions($operatorPermissions);
        $operator->menuActions()->sync($operatorActions);
        $operator->menus()->sync(SysMenu::whereIn('permission_prefix', ['guru', 'siswa'])->pluck('id')->toArray());
    }

    private function seedActions(SysMenu $menu, array $actions): void
    {
        foreach ($actions as $item) {
            $permissionName = "{$menu->permission_prefix}.{$item['action']}";

            SysMenuAction::firstOrCreate(
                ['menu_id' => $menu->id, 'action' => $item['action']],
                [
                    'label'           => $item['label'],
                    'permission_name' => $permissionName,
                    'order'           => $item['order'],
                ]
            );
            // Spatie permission also created by model booted() hook
        }
    }
}
