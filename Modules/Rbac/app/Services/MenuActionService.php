<?php

namespace Modules\Rbac\Services;

use App\Models\SysMenu;
use App\Models\SysUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MenuActionService
{
    // ── Public API ────────────────────────────────────────────────────────────

    /**
     * PURPOSE:
     *   Get the full sidebar menu tree for a user, with only the actions
     *   that user is permitted to perform already filtered in.
     *   Result is cached per-user for 10 minutes.
     *
     * USE IT IN:
     *   - A View Composer / AppServiceProvider to share $menus with all views
     *   - Sidebar blade component
     *
     * EXAMPLE:
     *   // AppServiceProvider::boot()
     *   View::composer('*', function ($view) {
     *       if (auth()->check()) {
     *           $view->with('sidebarMenus', MenuActionService::forUser(auth()->user()));
     *       }
     *   });
     *
     *   // sidebar.blade.php
     *   @foreach ($sidebarMenus as $menu)
     *       <a href="{{ $menu->url }}">{{ $menu->label_name_en }}</a>
     *       @foreach ($menu->permitted_actions as $action)
     *           <span class="badge">{{ $action->label }}</span>
     *       @endforeach
     *   @endforeach
     */
    public static function forUser($user): Collection
    {
        $cacheKey = "menu_actions_user_{$user->id}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user) {
            return SysMenu::with(['actions', 'children.actions'])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('sort_num')
                ->get()
                ->filter(fn($menu) => $menu->canAccess($user))
                ->map(fn($menu) => static::attachPermittedActions($menu, $user))
                ->values();
        });
    }

    /**
     * PURPOSE:
     *   Get only the actions the current user can perform on ONE specific menu,
     *   identified by its permission_prefix.
     *
     * USE IT IN:
     *   - Any page/controller that needs to know what buttons to show
     *   - Pass $actions to the view and iterate them to render buttons
     *
     * EXAMPLE (in a controller):
     *   public function index()
     *   {
     *       $actions = MenuActionService::actionsFor(auth()->user(), 'guru');
     *       return view('guru.index', [
     *           'gurus'   => Guru::paginate(25),
     *           'actions' => $actions,
     *       ]);
     *   }
     *
     * EXAMPLE (in a blade):
     *   @foreach ($actions->where('action', 'create') as $act)
     *       <a href="{{ route($act->route_name) }}" class="btn btn-primary">
     *           {{ $act->label }}
     *       </a>
     *   @endforeach
     */
    public static function actionsFor($user, string $prefix): Collection
    {
        $menu = SysMenu::with('actions')
            ->where('permission_prefix', $prefix)
            ->first();

        if (!$menu) {
            return collect();
        }

        return $menu->actions
            ->filter(fn($action) => $user->can($action->permission_name))
            ->values();
    }

    /**
     * PURPOSE:
     *   Get ONLY the "page-level" permitted actions (create, export, import…)
     *   for rendering the action bar at the top of a listing page.
     *
     * EXAMPLE:
     *   $pageActions = MenuActionService::pageActionsFor(auth()->user(), 'guru');
     *   // → [create action, export action]  — NO edit/delete (those are row-level)
     */
    public static function pageActionsFor($user, string $prefix): Collection
    {
        return static::actionsFor($user, $prefix)
            ->filter(fn($a) => $a->isPageLevel());
    }

    /**
     * PURPOSE:
     *   Get ONLY the "row-level" permitted actions (edit, delete, approve…)
     *   for rendering buttons inside each data table row.
     *
     * EXAMPLE:
     *   $rowActions = MenuActionService::rowActionsFor(auth()->user(), 'guru');
     *   // → [edit action, delete action]
     *
     *   // in blade table:
     *   @foreach ($gurus as $guru)
     *       <tr>
     *           <td>{{ $guru->name }}</td>
     *           <td>
     *               @foreach ($rowActions as $act)
     *                   @include('rbac::components.action-button', ['action' => $act, 'model' => $guru])
     *               @endforeach
     *           </td>
     *       </tr>
     *   @endforeach
     */
    public static function rowActionsFor($user, string $prefix): Collection
    {
        return static::actionsFor($user, $prefix)
            ->filter(fn($a) => $a->isRowLevel());
    }

    /**
     * PURPOSE:
     *   Get ALL actions defined for a menu (regardless of user permissions).
     *   Used by the route registrar to know which routes need middleware.
     *
     * USE IT IN:
     *   - DynamicRouteRegistrar (see below) — not in normal request cycle
     */
    public static function allActionsFor(string $prefix): Collection
    {
        $menu = SysMenu::with('actions')
            ->where('permission_prefix', $prefix)
            ->first();

        return $menu ? $menu->actions->sortBy('order') : collect();
    }

    /**
     * PURPOSE:
     *   Bust the cached menu tree for a specific user.
     *   Call this in your RoleActions::syncAll() after syncing permissions.
     *
     * EXAMPLE:
     *   MenuActionService::forgetCache($user->id);
     */
    public static function forgetCache(int $userId): void
    {
        Cache::forget("menu_actions_user_{$userId}");
    }

    /**
     * Bust cache for ALL users — call after any permission/role structure change.
     */
    public static function forgetAllCache(): void
    {
        // If using cache tags (Redis):
        // Cache::tags(['menu_actions'])->flush();

        // Without tags — iterate known user IDs:
        SysUser::pluck('id')->each(fn($id) => static::forgetCache($id));
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private static function attachPermittedActions(SysMenu $menu, $user): SysMenu
    {
        $menu->permitted_actions = $menu->actions
            ->filter(fn($a) => $user->can($a->permission_name))
            ->values();

        $menu->setRelation(
            'children',
            $menu->children
                ->filter(fn($child) => $child->canAccess($user))
                ->map(fn($child) => static::attachPermittedActions($child, $user))
                ->values()
        );

        return $menu;
    }
}
