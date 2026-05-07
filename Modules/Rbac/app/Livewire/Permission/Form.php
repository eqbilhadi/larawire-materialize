<?php

namespace Modules\Rbac\Livewire\Permission;

use App\Models\SysMenu;
use App\Models\SysPermission;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Rbac\Livewire\Validations\PermissionValidation;
use Modules\Rbac\Models\SysMenuAction;
use Modules\Rbac\Services\Actions\Permission\PermissionActions;
use Modules\Rbac\Services\Actions\Permission\PermissionMenuActions;

class Form extends Component
{
    use PermissionValidation;

    // ── Shared state ──────────────────────────────────────────────────────
    public string $modalType  = 'permission';
    public string $actionForm = 'Add';

    // ── Standalone permission form ────────────────────────────────────────
    public array $form = [
        'name'  => '',
        'group' => '',
    ];
    public ?SysPermission $comPermission = null;

    // ── Menu action form ──────────────────────────────────────────────────
    public array $actionData = [
        'menu_id'      => '',
        'action'       => '',
        'label'        => '',
        'route_name'   => '',
        'route_method' => 'GET',
    ];
    public ?SysMenuAction $menuAction = null;
    public array $menuOptions = [];

    // ── Open: standalone permission modal ─────────────────────────────────

    #[On('open-permission-form')]
    public function openPermissionModal(?SysPermission $comPermission = null): void
    {
        $this->resetAll();
        $this->modalType    = 'permission';
        $this->comPermission = $comPermission;

        if ($this->comPermission->exists) {
            $this->actionForm   = 'Edit';
            $this->form['name']  = $this->comPermission->name;
            $this->form['group'] = $this->comPermission->group;
        } else {
            $this->actionForm = 'Add';
        }

        $this->dispatch('open-modal');
    }

    // ── Open: menu action modal ───────────────────────────────────────────

    /**
     * Open to ADD a new action to a specific menu.
     * Called with: $dispatch('open-action-form', { menuId: 1 })
     */
    #[On('open-action-form')]
    public function openActionModal(int $menuId, ?int $menuActionId = null): void
    {
        $this->resetAll();
        $this->modalType = 'action';

        $this->menuOptions = SysMenu::whereNotNull('permission_prefix')
            ->orderBy('sort_num')
            ->get()
            ->map(fn($m) => ['id' => $m->id, 'label' => $m->label_name_en . ' (' . $m->permission_prefix . ')'])
            ->toArray();

        if ($menuActionId) {
            $this->menuAction              = SysMenuAction::findOrFail($menuActionId);
            $this->actionForm              = 'Edit';
            $this->actionData['menu_id']      = $this->menuAction->menu_id;
            $this->actionData['action']       = $this->menuAction->action;
            $this->actionData['label']        = $this->menuAction->label;
            $this->actionData['route_name']   = $this->menuAction->route_name ?? '';
            $this->actionData['route_method'] = $this->menuAction->route_method ?? 'GET';
        } else {
            $this->actionForm                = 'Add';
            $this->actionData['menu_id']     = $menuId;
        }

        $this->dispatch('open-modal');
    }

    // ── Save ──────────────────────────────────────────────────────────────

    public function save(): void
    {
        if ($this->modalType === 'permission') {
            $this->savePermission();
        } else {
            $this->saveAction();
        }
    }

    protected function savePermission(): void
    {
        $this->validateOnly('form.name');
        $this->validateOnly('form.group');

        try {
            (new PermissionActions($this->form, $this->comPermission))->handle();
            $this->dispatch('saved');
            flash()->success($this->actionForm . ' permission successfully');
        } catch (\Exception $err) {
            flash()->error($err->getMessage());
            Log::error($err->getMessage());
        }
    }

    protected function saveAction(): void
    {
        $this->validateOnly('actionData.menu_id');
        $this->validateOnly('actionData.action');
        $this->validateOnly('actionData.label');

        try {
            (new PermissionMenuActions($this->actionData, $this->menuAction))->handle();
            $this->dispatch('saved');
            flash()->success($this->actionForm . ' action successfully');
        } catch (\Exception $err) {
            flash()->error($err->getMessage());
            Log::error($err->getMessage());
        }
    }

    // ── Computed: preview permission name ─────────────────────────────────

    public function getPermissionPreviewProperty(): string
    {
        $menuId = $this->actionData['menu_id'] ?? null;
        $slug   = trim($this->actionData['action'] ?? '');

        if (!$menuId || !$slug) {
            return '';
        }

        $menu = SysMenu::find($menuId);
        return $menu ? $menu->permission_prefix . '.' . $slug : '';
    }

    // ── Reset ─────────────────────────────────────────────────────────────

    protected function resetAll(): void
    {
        $this->resetValidation();
        $this->form       = ['name' => '', 'group' => ''];
        $this->actionData = ['menu_id' => '', 'action' => '', 'label' => '', 'route_name' => '', 'route_method' => 'GET'];
        $this->comPermission = null;
        $this->menuAction    = null;
    }

    public function render()
    {
        return view('rbac::livewire.permission.form');
    }
}
