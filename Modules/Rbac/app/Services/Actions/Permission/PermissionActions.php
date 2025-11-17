<?php

namespace Modules\Rbac\Services\Actions\Permission;

use App\Models\SysPermission;
use Illuminate\Support\Arr;
use App\Models\SysRole;
use Modules\Rbac\Services\Actions\ActionsService;

class PermissionActions extends ActionsService
{

    protected array $data = [];

    protected ?SysPermission $sysPermission = null;

    /**
     * Method __construct
     *
     * @param ?SysPermission $sysPermission [explicite description]
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function __construct(array $data = [], ?SysPermission $sysPermission = null)
    {
        $this->data = $data;

        $this->sysPermission = $sysPermission;
    }

    /**
     * Method save
     *
     * @return void
     */
    public function save(): void
    {
        if (!$this->sysPermission->exists) {
            $this->create();
        } else {
            $this->update();
        }
    }

    /**
     * Method create
     *
     * @return static
     */
    protected function create(): static
    {
        $this->sysPermission = SysPermission::create($this->getActionsDataSysPermission());

        return $this;
    }

    /**
     * Method update
     *
     * @return static
     */
    protected function update(): static
    {
        $this->sysPermission->update($this->getActionsDataSysPermission());

        return $this;
    }

    /**
     * Method getActionsDataSysPermission
     *
     * @return array
     */
    protected function getActionsDataSysPermission(): array
    {
        // set data
        $data = [
            'name' => Arr::get($this->data, 'name'),
            'group' => Arr::get($this->data, 'group'),
        ];

        $data = array_map(function ($value) {
            return $value === "" ? null : $value;
        }, $data);

        return $data;
    }
}
