<?php

namespace Modules\Rbac\Services\Actions\User;

use App\Models\SysUser;
use Modules\Rbac\Services\Actions\ActionsService;

class UserDestroy extends ActionsService
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
            SysUser::destroy($this->id);
        } else {
            SysUser::find($this->id)?->delete();
        }
    }
}
