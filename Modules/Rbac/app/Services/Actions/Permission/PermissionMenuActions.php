<?php

namespace Modules\Rbac\Services\Actions\Permission;

use App\Models\SysMenu;
use Illuminate\Support\Arr;
use Modules\Rbac\Models\SysMenuAction;
use Modules\Rbac\Services\Actions\ActionsService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionMenuActions extends ActionsService
{
    protected array $data = [];
    protected ?SysMenuAction $menuAction;

    public function __construct(array $data = [], ?SysMenuAction $menuAction = null)
    {
        $this->data       = $data;
        $this->menuAction = $menuAction;
    }

    public function save(): void
    {
        if (!$this->menuAction || !$this->menuAction->exists) {
            $this->create();
        } else {
            $this->update();
        }
    }

    protected function create(): static
    {
        $menu           = SysMenu::findOrFail(Arr::get($this->data, 'menu_id'));
        $slug           = trim(Arr::get($this->data, 'action'));
        $permissionName = $menu->permission_prefix . '.' . $slug;

        // SysMenuAction::created() booted hook auto-creates the spatie permission
        $this->menuAction = SysMenuAction::create([
            'menu_id'         => $menu->id,
            'action'          => $slug,
            'label'           => Arr::get($this->data, 'label'),
            'permission_name' => $permissionName,
            'route_name'      => Arr::get($this->data, 'route_name') ?: null,
            'route_method'    => strtoupper(Arr::get($this->data, 'route_method', 'GET')),
            'order'           => SysMenuAction::where('menu_id', $menu->id)->max('order') + 1,
        ]);

        return $this;
    }

    protected function update(): static
    {
        $menu           = SysMenu::findOrFail(Arr::get($this->data, 'menu_id'));
        $slug           = trim(Arr::get($this->data, 'action'));
        $newPermName    = $menu->permission_prefix . '.' . $slug;

        // Rename spatie permission if the name changed
        if ($this->menuAction->permission_name !== $newPermName) {
            Permission::where('name', $this->menuAction->permission_name)
                ->update(['name' => $newPermName]);
        }

        $this->menuAction->update([
            'menu_id'         => $menu->id,
            'action'          => $slug,
            'label'           => Arr::get($this->data, 'label'),
            'permission_name' => $newPermName,
            'route_name'      => Arr::get($this->data, 'route_name') ?: null,
            'route_method'    => strtoupper(Arr::get($this->data, 'route_method', 'GET')),
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this;
    }
}
