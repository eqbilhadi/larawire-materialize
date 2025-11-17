<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class SysRole extends SpatieRole
{
    /**
     * Method menus
     *
     * @return BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(SysMenu::class, 'sys_menu_roles', 'role_id', 'menu_id');
    }
}
