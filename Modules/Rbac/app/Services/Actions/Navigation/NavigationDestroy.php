<?php

namespace Modules\Rbac\Services\Actions\Navigation;

use App\Models\SysMenu;
use Modules\Rbac\Services\Actions\ActionsService;

class NavigationDestroy extends ActionsService
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
            SysMenu::destroy($this->id);
        } else {
            SysMenu::find($this->id)->delete();
        }
    }
}
