<?php

namespace App\View\Components\Layouts\App;

use App\Models\SysMenu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

class Aside extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $menus = $this->getCacheMenus();

        return view('components.layouts.app.aside', [
            'menus' => $menus,
        ]);
    }

    protected function getCacheMenus()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        return Cache::remember($user->id . '_menus', now()->addhours(8), function () use ($user) {
            return SysMenu::query()
                ->whereHas('roles', fn($query) => $query->whereIn('role_id', $user->roles->pluck('id')))
                ->whereNull('parent_id')
                ->whereIsActive(true)
                ->with([
                    'children' => function ($query) use ($user) {
                        $query->whereHas('roles', fn($query) => $query->whereIn('role_id', $user->roles->pluck('id')));
                        $query->whereIsActive(true)->orderBy('sort_num', 'asc');
                    }
                ])
                ->orderBy('sort_num', 'asc')
                ->get();
        });
    }
}
