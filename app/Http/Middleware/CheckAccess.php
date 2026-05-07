<?php

namespace App\Http\Middleware;

use App\Models\SysMenu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Rbac\Models\SysMenuAction;
use Symfony\Component\HttpFoundation\Response;

class CheckAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\SysUser $user */
        $user = Auth::user();

        if (!$user) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $userRoleIds = $user->roles->pluck('id')->toArray();
        $currentController = $request->route()->getControllerClass();
        $currentRouteName = $request->route()->getName();

        $menu = SysMenu::where('controller_name', $currentController)
            ->where('is_active', 1)
            ->whereHas('roles', fn($q) => $q->whereIn('role_id', $userRoleIds))
            ->first();

        if (!$menu) {
            abort(403, 'You do not have access to this menu.');
        }

        $menuAction = SysMenuAction::where('route_name', $currentRouteName)
            ->where('menu_id', $menu->id)
            ->first();

        if ($menuAction && !$user->can($menuAction->permission_name)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}
