<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

class SysMenu extends Model
{
    protected $table = 'sys_menus';

    protected $fillable = [
        'parent_id',
        'sort_num',
        'icon',
        'label_name_en',
        'label_name_pt',
        'label_name_tl',
        'controller_name',
        'route_name',
        'url',
        'is_active',
        'is_divider',
    ];

    protected $appends = [
        'link',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_divider' => 'boolean',
        ];
    }

    public function getLinkAttribute()
    {
        return Route::has($this->route_name)
            ? route($this->route_name)
            : route('home'); // fallback
    }

    /**
     * Method roles
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SysRole::class, 'sys_menu_roles', 'menu_id', 'role_id');
    }

    /**
     * Method parent
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id')->with('parent', 'roles')->orderBy('sort_num', 'asc');
    }

    /**
     * Method children
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id')->with('children')->orderBy('sort_num', 'asc');
    }
}
