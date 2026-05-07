<?php

namespace Modules\Rbac\Services\Actions\Role;

use Illuminate\Support\Arr;
use App\Models\SysRole;
use App\Models\SysPermission;
use Modules\Rbac\Models\SysMenuAction;
use Modules\Rbac\Services\Actions\ActionsService;
use Spatie\Permission\PermissionRegistrar;

class RoleActions extends ActionsService
{
    protected array $data = [];
    public ?SysRole $sysRole = null;

    public function __construct(array $data = [], ?SysRole $sysRole = null)
    {
        $this->data    = $data;
        $this->sysRole = $sysRole;
    }

    public function save(): static
    {
        return !$this->sysRole->exists ? $this->create() : $this->update();
    }

    protected function create(): static
    {
        $this->sysRole = SysRole::create($this->getRoleData());
        $this->syncAll();
        return $this;
    }

    protected function update(): static
    {
        $this->sysRole->update($this->getRoleData());
        $this->syncAll();
        return $this;
    }

    /**
     * Sync menus, dynamic menu actions, and legacy permissions together.
     *
     * $this->data['menu_actions'] = ['menu_action_id_1', 'menu_action_id_2', ...]
     * $this->data['permissions']  = ['spatie_permission_id_1', ...]  (legacy / extra)
     * $this->data['menus']        = ['menu_id_1', ...]
     */
    protected function syncAll(): static
    {
        // 1. Sync menu access
        $selectedMenus = Arr::get($this->data, 'menus', []);
        $this->sysRole->menus()->sync($selectedMenus);

        // 2. Select permission from menu actions
        $selectedActionIds = Arr::get($this->data, 'menu_actions', []);

        // 3. Build permission list:
        //    a) permissions from selected menu actions
        $actionPermissions = SysMenuAction::whereIn('id', $selectedActionIds)
            ->pluck('permission_name')
            ->toArray();

        //    b) legacy spatie permissions selected manually
        $legacyPermissionIds   = Arr::get($this->data, 'permissions', []);
        $legacyPermissionNames = SysPermission::whereIn('id', $legacyPermissionIds)
            ->pluck('name')
            ->toArray();

        $allPermissions = array_unique(array_merge(
            $actionPermissions,
            $legacyPermissionNames
        ));

        $this->sysRole->syncPermissions($allPermissions);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this;
    }

    protected function getRoleData(): array
    {
        $data = ['name' => Arr::get($this->data, 'name')];
        return array_map(fn($v) => $v === '' ? null : $v, $data);
    }
}
