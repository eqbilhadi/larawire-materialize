<?php

namespace Modules\Rbac\Services\Actions\Role;

use App\Models\SysPermission;
use Illuminate\Support\Arr;
use App\Models\SysRole;
use Modules\Rbac\Services\Actions\ActionsService;

class RoleActions extends ActionsService
{

    protected array $data = [];

    public ?SysRole $sysRole = null;

    /**
     * Method __construct
     *
     * @param ?SysRole $sysRole [explicite description]
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function __construct(array $data = [], ?SysRole $sysRole = null)
    {
        $this->data = $data;

        $this->sysRole = $sysRole;
    }

    /**
     * Method save
     *
     * @return void
     */
    public function save(): static
    {
        if (!$this->sysRole->exists) {
            return $this->create();
        } else {
            return $this->update();
        }
    }

    /**
     * Method create
     *
     * @return static
     */
    protected function create(): static
    {
        $this->sysRole = SysRole::create($this->getActionsDataSysRole());

        $this->syncPermissionsMenus();

        return $this;
    }

    /**
     * Method update
     *
     * @return static
     */
    protected function update(): static
    {
        $this->sysRole->update($this->getActionsDataSysRole());

        $this->syncPermissionsMenus();

        return $this;
    }

    /**
     * Method getActionsDataSysRole
     *
     * @return array
     */
    protected function getActionsDataSysRole(): array
    {
        // set data
        $data = [
            'name' => Arr::get($this->data, 'name'),
        ];

        $data = array_map(function ($value) {
            return $value === "" ? null : $value;
        }, $data);

        return $data;
    }

    protected function syncPermissionsMenus(): static
    {
        $selectedMenus = Arr::get($this->data, 'menus', []);
        $selectedPermissions = Arr::get($this->data, 'permissions', []);
        $permissionNames = SysPermission::whereIn('id', $selectedPermissions)
            ->pluck('name')
            ->toArray();

        $this->sysRole->syncPermissions($permissionNames);
        $this->sysRole->menus()->sync($selectedMenus);

        return $this;
    }
}
