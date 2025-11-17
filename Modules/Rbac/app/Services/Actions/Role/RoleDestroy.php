<?php

namespace Modules\Rbac\Services\Actions\Role;

use App\Models\SysRole;
use Modules\Rbac\Services\Actions\ActionsService;

class RoleDestroy extends ActionsService
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
            SysRole::destroy($this->id);
        } else {
            SysRole::find($this->id)->delete();
        }
    }
}
