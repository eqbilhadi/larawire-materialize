<?php

namespace Modules\Rbac\Services\Actions\Permission;

use App\Models\SysPermission;
use Modules\Rbac\Services\Actions\ActionsService;

class PermissionDestroy extends ActionsService
{
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Save action
     *
     * @return void
     */
    public function save(): void
    {
        if(is_array($this->id)) {
            SysPermission::destroy($this->id);
        } else {
            SysPermission::find($this->id)->delete();
        }
    }
}
