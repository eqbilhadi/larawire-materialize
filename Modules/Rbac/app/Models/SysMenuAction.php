<?php

namespace Modules\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class SysMenuAction extends Model
{
    protected $table = 'sys_menu_actions';

    protected $fillable = [
        'menu_id',
        'action',
        'label',
        'permission_name',
        'route_name',
        'order',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SysMenu::class, 'menu_id');
    }

    protected static function booted(): void
    {
        // Auto-create spatie permission when action is created
        static::created(function (self $action) {
            Permission::firstOrCreate(
                [
                    'name'       => $action->permission_name,
                    'group'      => $action->menu->permission_prefix ?? null,
                    'guard_name' => 'web',
                ],
                ['type' => 'menu_action']
            );
        });

        // Auto-delete spatie permission when action is deleted
        static::deleted(function (self $action) {
            Permission::where('name', $action->permission_name)->delete();
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        });
    }

    public function hasRoute(): bool
    {
        return filled($this->route_name) && Route::has($this->route_name);
    }
}
