<?php

namespace Modules\Rbac\Traits;

use Modules\Rbac\Models\SysMenuAction;

/**
 * Add this trait to your App\Models\SysMenu model.
 *
 * Usage in App\Models\SysMenu:
 *   use Modules\Rbac\Traits\HasMenuActions;   // already added above on SysRole
 *   // for SysMenu use HasDynamicActions instead:
 *   use Modules\Rbac\Traits\HasDynamicActions;
 *   class SysMenu extends Model {
 *       use HasDynamicActions;
 *   }
 */
trait HasDynamicActions
{
    /**
     * Dynamic actions belonging to this menu.
     */
    public function actions()
    {
        return $this->hasMany(SysMenuAction::class, 'menu_id')->orderBy('order');
    }

    /**
     * Permission name that simply gates access to the menu sidebar item.
     * Returns null when no prefix is defined (legacy menus without prefix).
     */
    public function accessPermissionName(): ?string
    {
        $prefix = $this->permission_prefix ?? null;
        return $prefix ? "{$prefix}.access" : null;
    }

    /**
     * Check whether the given user can see this menu at all.
     * Falls back to true for menus with no prefix (backward-compatible).
     */
    public function canAccess($user): bool
    {
        $perm = $this->accessPermissionName();
        return $perm === null || $user->can($perm);
    }

    /**
     * Check whether the given user has a specific action on this menu.
     *
     * Example: $menu->userCan($user, 'create')
     */
    public function userCan($user, string $action): bool
    {
        $prefix = $this->permission_prefix ?? null;
        if (!$prefix) return false;
        return $user->can("{$prefix}.{$action}");
    }
}
