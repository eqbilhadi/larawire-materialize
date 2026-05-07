<?php

namespace Modules\Rbac\Livewire\Role;

use App\Jobs\ForgetCacheMenu;
use Livewire\Component;
use App\Models\SysMenu;
use App\Models\SysPermission;
use App\Models\SysRole;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Rbac\Livewire\Validations\RoleValidation;
use Modules\Rbac\Services\Actions\Role\RoleActions;

class Form extends Component
{
    use RoleValidation;

    public string $name = '';
    public string $searchPermission = '';
    public string $searchMenu = '';

    public array $selectedPermissions = [];
    public array $selectedMenus = [];

    public array $selectedMenuActions = [];

    public SysRole $sysRole;
    public string  $action = 'Added';

    public function mount(?SysRole $sysRole = null): void
    {
        $this->sysRole = $sysRole;
        if ($this->sysRole->exists) {
            $this->fillForm();
            $this->action = 'Updated';
        }
    }

    public function fillForm(): void
    {
        $this->name = $this->sysRole->name;

        $this->selectedPermissions = $this->sysRole
            ->permissions()->where('type', 'standalone')
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $this->selectedMenus = $this->sysRole
            ->menus()
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        $this->selectedMenuActions = $this->sysRole
            ->permissions()->where('type', 'menu_action')
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    // ── Computed properties ───────────────────────────────────────────────────

    public function getPermissionsProperty(): Collection
    {
        return SysPermission::query()
            ->when($this->searchPermission, fn($q) =>  $q->where('name', 'like', '%' . $this->searchPermission . '%'))
            ->where('type', 'standalone')
            ->get()
            ->groupBy('group');
    }

    /**
     * Menus with their dynamic actions eager-loaded.
     */
    public function getMenusProperty(): Collection
    {
        return SysMenu::query()
            ->select('sys_menus.*')
            ->with(['children.actions', 'actions'])
            ->leftJoin('sys_menus as parent', 'sys_menus.parent_id', '=', 'parent.id')
            ->when($this->searchMenu, function ($query) {
                $query->where(function ($q) {
                    $q->where('sys_menus.label_name_en', 'like', '%' . $this->searchMenu . '%')
                        ->orWhere('sys_menus.label_name_pt', 'like', '%' . $this->searchMenu . '%')
                        ->orWhere('sys_menus.label_name_tl', 'like', '%' . $this->searchMenu . '%');
                });
            })
            ->where('sys_menus.is_active', true)
            ->orderByRaw('COALESCE(parent.sort_num, sys_menus.sort_num) ASC')
            ->orderByRaw('CASE WHEN sys_menus.parent_id IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('sys_menus.sort_num', 'ASC')
            ->get();
    }

    // ── Permission toggles ────────────────────────────────────────────────────

    public function toggleSelectAllPermissions(): void
    {
        $all = $this->permissions->flatten()->pluck('id')->toArray();
        $allSelected = count(array_intersect($all, $this->selectedPermissions)) === count($all);

        $this->selectedPermissions = $allSelected
            ? array_diff($this->selectedPermissions, $all)
            : array_unique(array_merge($this->selectedPermissions, $all));
    }

    public function togglePermissionGroup(string $groupName): void
    {
        $ids = $this->permissions
            ->get($groupName, collect())
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $allSelected = count(array_intersect($ids, $this->selectedPermissions)) === count($ids);

        $this->selectedPermissions = $allSelected
            ? array_diff($this->selectedPermissions, $ids)
            : array_values(array_unique(array_merge($this->selectedPermissions, $ids)));
    }

    // ── Menu toggles ─────────────────────────────────────────────────────────

    public function toggleSelectAllMenus(): void
    {
        $allMenuIds   = SysMenu::pluck('id')->toArray();
        $allActionIds = array_map('strval', \Modules\Rbac\Models\SysMenuAction::pluck('id')->toArray());

        $allMenusSelected   = count(array_intersect($allMenuIds, $this->selectedMenus)) === count($allMenuIds);
        $allActionsSelected = count(array_intersect($allActionIds, $this->selectedMenuActions)) === count($allActionIds);
        $allSelected        = $allMenusSelected && $allActionsSelected;

        if ($allSelected) {
            $this->selectedMenus       = [];
            $this->selectedMenuActions = [];
        } else {
            $this->selectedMenus       = $allMenuIds;
            $this->selectedMenuActions = $allActionIds;
        }
    }

    public function toggleMenuAndChildren(int $menuId): void
    {
        $menu = SysMenu::with('children.actions', 'children.children.actions', 'actions')->find($menuId);
        if (!$menu) return;

        $allIds = $this->getAllChildMenuIds($menu, [$menuId]);
        $state  = $this->getMenuState($menu, $allIds);

        // Kumpulkan semua action IDs dari menu ini dan seluruh child-nya
        $allActionIds = array_map('strval', $this->getAllMenuActionIds($menu));

        if ($state['all']) {
            // Uncheck: hapus menu dan semua actions-nya
            $this->selectedMenus = array_values(array_diff($this->selectedMenus, $allIds));
            $this->selectedMenuActions = array_values(
                array_filter($this->selectedMenuActions, fn($v) => !in_array($v, $allActionIds))
            );

            foreach ($this->getParentIds($menu) as $pid) {
                $parent     = SysMenu::with('children')->find($pid);
                $parentKids = $this->getAllChildMenuIds($parent, [$pid]);
                if (!array_intersect($parentKids, $this->selectedMenus)) {
                    $this->selectedMenus = array_values(array_diff($this->selectedMenus, [$pid]));
                }
            }
        } else {
            // Check: tambahkan menu, parent-nya, dan semua actions-nya
            $this->selectedMenus = array_values(array_unique(array_merge(
                $this->selectedMenus,
                $allIds,
                $this->getParentIds($menu)
            )));

            $this->selectedMenuActions = array_values(array_unique(
                array_merge($this->selectedMenuActions, $allActionIds)
            ));
        }
    }

    // ── Dynamic action toggles ────────────────────────────────────────────────

    /**
     * Toggle a single menu action for this role.
     */
    public function toggleMenuAction(int $menuActionId): void
    {
        $id = (string) $menuActionId;

        if (in_array($id, $this->selectedMenuActions)) {
            $this->selectedMenuActions = array_values(
                array_filter($this->selectedMenuActions, fn($v) => $v !== $id)
            );
        } else {
            $this->selectedMenuActions[] = $id;
        }
    }

    /**
     * Toggle all actions of a single menu at once.
     */
    public function toggleAllMenuActions(int $menuId): void
    {
        $menu    = SysMenu::with('children.actions', 'actions')->find($menuId);
        $allIds  = $this->getAllMenuActionIds($menu);
        $strIds  = array_map('strval', $allIds);

        $allSelected = count(array_intersect($strIds, $this->selectedMenuActions)) === count($strIds);

        $this->selectedMenuActions = $allSelected
            ? array_values(array_filter($this->selectedMenuActions, fn($v) => !in_array($v, $strIds)))
            : array_values(array_unique(array_merge($this->selectedMenuActions, $strIds)));
    }

    /**
     * Retrieve state of action checkboxes for a menu (for indeterminate support).
     */
    public function getMenuActionState(int $menuId): array
    {
        $menu    = SysMenu::with('children.actions', 'actions')->find($menuId);
        $allIds  = array_map('strval', $this->getAllMenuActionIds($menu));
        $selCount = count(array_intersect($allIds, $this->selectedMenuActions));
        $total    = count($allIds);

        return [
            'all'  => $total > 0 && $selCount === $total,
            'some' => $selCount > 0 && $selCount < $total,
            'none' => $selCount === 0,
        ];
    }

    // ── Save ─────────────────────────────────────────────────────────────────

    public function save()
    {
        $this->validate();

        $form = [
            'name'         => $this->name,
            'menus'        => $this->selectedMenus,
            'permissions'  => $this->selectedPermissions,
            'menu_actions' => $this->selectedMenuActions,
        ];

        try {
            (new RoleActions($form, $this->sysRole))->handle();
            dispatch(new ForgetCacheMenu());

            flash()->success($this->action . ' role successfully');
            return $this->redirect(route('rbac.role.index'));
        } catch (\Exception $err) {
            flash()->error('Something went wrong, try again later!');
            Log::error($err->getMessage());
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getMenuState(SysMenu $menu, ?array $allIds = null): array
    {
        $allIds       = $allIds ?? $this->getAllChildMenuIds($menu, [$menu->id]);
        $allIds       = empty($allIds) ? [$menu->id] : $allIds;
        $selectedCount = count(array_intersect($allIds, $this->selectedMenus));
        $total         = count($allIds) > 1 ? (count($allIds) - 1) : count($allIds);
        $selected       = count($allIds) > 1 ? $selectedCount - 1 : $selectedCount;
        return [
            'all'  => $selected === $total,
            'some' => $selected > 0 && $selected < $total,
            'none' => $selected === 0,
            'ids'  => $allIds,
        ];
    }

    private function getAllChildMenuIds(SysMenu $menu, array $ids = []): array
    {
        foreach ($menu->children as $child) {
            $ids[] = $child->id;
            if ($child->children->isNotEmpty()) {
                $ids = $this->getAllChildMenuIds($child, $ids);
            }
        }
        return $ids;
    }

    private function getParentIds(SysMenu $menu, array $ids = []): array
    {
        if ($menu->parent_id) {
            $ids[]  = $menu->parent_id;
            $parent = SysMenu::find($menu->parent_id);
            if ($parent) {
                return $this->getParentIds($parent, $ids);
            }
        }
        return $ids;
    }

    private function getAllMenuActionIds(SysMenu $menu): array
    {
        $ids = $menu->actions->pluck('id')->toArray();
        foreach ($menu->children as $child) {
            $ids = array_merge($ids, $this->getAllMenuActionIds($child));
        }
        return $ids;
    }

    public function render()
    {
        return view('rbac::livewire.role.form');
    }
}
