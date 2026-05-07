<?php

namespace Modules\Rbac\Traits;

use Modules\Rbac\Models\SysMenuAction;

/**
 * Add this trait to your App\Models\SysRole model.
 *
 * Usage in App\Models\SysRole:
 *   use Modules\Rbac\Traits\HasMenuActions;
 *   class SysRole extends Model {
 *       use HasMenuActions;
 *   }
 */
trait HasMenuActions
{
    public function menuActions()
    {
        return $this->belongsToMany(
            SysMenuAction::class,
            'role_menu_action',
            'role_id',
            'menu_action_id'
        );
    }
}
