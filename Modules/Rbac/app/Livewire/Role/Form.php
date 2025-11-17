<?php

namespace Modules\Rbac\Livewire\Role;

use App\Jobs\ForgetCacheMenu;
use Livewire\Component;
use App\Models\SysMenu;
use App\Models\SysPermission;
use App\Models\SysRole;
use Illuminate\Support\Facades\DB;
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

    public SysRole $sysRole;
    public string $action = "Added";

    public function mount(?SysRole $sysRole = null)
    {
        $this->sysRole = $sysRole;
        if ($this->sysRole->exists) {
            $this->fillForm();
            $this->action = "Updated";
        }
    }

    public function fillForm()
    {
        $this->name = $this->sysRole->name;

        $this->selectedPermissions = $this->sysRole
            ->permissions()
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $this->selectedMenus = $this->sysRole
            ->menus()
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();
    }


    public function getPermissionsProperty(): Collection
    {
        return SysPermission::query()
            ->when(
                $this->searchPermission,
                fn($q) =>
                $q->where('name', 'like', '%' . $this->searchPermission . '%')
            )
            ->get()
            ->groupBy('group');
    }

    public function getMenusProperty(): Collection
    {
        return SysMenu::query()
            ->whereNull('parent_id')
            ->when(
                $this->searchMenu,
                fn($q) =>
                $q->where('label_name_en', 'like', '%' . $this->searchMenu . '%')
            )
            ->with('children')
            ->orderBy('sort_num')
            ->get();
    }

    public function toggleSelectAllPermissions()
    {
        $allPermissionIds = $this->permissions->flatten()->pluck('id')->toArray();

        $allSelected = count(array_intersect($allPermissionIds, $this->selectedPermissions)) === count($allPermissionIds);

        if ($allSelected) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, $allPermissionIds);
        } else {
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $allPermissionIds));
        }
    }

    public function toggleSelectAllMenus()
    {
        $allMenuIds = SysMenu::pluck('id')->toArray();

        $allSelected = count(array_intersect($allMenuIds, $this->selectedMenus)) === count($allMenuIds);

        if ($allSelected) {
            $this->selectedMenus = [];
        } else {
            $this->selectedMenus = $allMenuIds;
        }
    }

    public function togglePermissionGroup(string $groupName, bool $checked)
    {
        $idsInGroup = $this->permissions
            ->get($groupName, collect())
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $allSelected = count(array_intersect($idsInGroup, $this->selectedPermissions)) === count($idsInGroup);

        if ($allSelected) {
            $this->selectedPermissions = array_diff($this->selectedPermissions, $idsInGroup);
        } else {
            $this->selectedPermissions = array_values(array_unique(array_merge($this->selectedPermissions, $idsInGroup)));
        }
    }

    private function getParentIds(SysMenu $menu, array $ids = []): array
    {
        if ($menu->parent_id) {
            $ids[] = $menu->parent_id;
            $parent = SysMenu::find($menu->parent_id);
            if ($parent) {
                return $this->getParentIds($parent, $ids);
            }
        }

        return $ids;
    }

    public function toggleMenuAndChildren(int $menuId)
    {
        $menu = SysMenu::find($menuId);
        if (!$menu) return;

        $allIds = $this->getAllChildMenuIds($menu, [$menuId]);

        $state = $this->getMenuState($menu, $allIds);

        if ($state['all']) {
            $this->selectedMenus = array_diff($this->selectedMenus, $allIds);
            $parentIds = $this->getParentIds($menu);

            foreach ($parentIds as $pid) {
                $parent = SysMenu::find($pid);
                $parentChildren = $this->getAllChildMenuIds($parent, [$pid]);

                if (!array_intersect($parentChildren, $this->selectedMenus)) {
                    $this->selectedMenus = array_diff($this->selectedMenus, [$pid]);
                }
            }
        } else {
            $this->selectedMenus = array_unique(array_merge($this->selectedMenus, $allIds));
            $parentIds = $this->getParentIds($menu);
            $this->selectedMenus = array_unique(array_merge($this->selectedMenus, $parentIds));
        }

        $this->selectedMenus = array_values($this->selectedMenus);
    }


    public function save()
    {
        $this->validate();

        $form = [
            'name' => $this->name,
            'menus' => $this->selectedMenus,
            'permissions' => $this->selectedPermissions,
        ];

        try {
            $role = (new RoleActions($form, $this->sysRole))->handle();

            dispatch(new ForgetCacheMenu());
            flash()->success($this->action . ' role successfully');
            return $this->redirect(route('rbac.role.index'));
        } catch (\Exception $err) {
            flash()->error('Something went wrong, try again later!');
            Log::info($err->getMessage());
        }
    }

    public function getMenuState(SysMenu $menu, ?array $allIds = null): array
    {
        if (is_null($allIds)) {
            $allIds = $this->getAllChildMenuIds($menu, [$menu->id]);
        }

        if (empty($allIds)) {
            $allIds = [$menu->id];
        }

        $selectedCount = count(array_intersect($allIds, $this->selectedMenus));
        $totalCount = count($allIds);

        $all = $selectedCount === $totalCount;
        $none = $selectedCount === 0;
        $some = !$all && !$none;

        return ['all' => $all, 'some' => $some, 'none' => $none, 'ids' => $allIds];
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

    public function render()
    {
        return view('rbac::livewire.role.form');
    }
}
