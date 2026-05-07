<?php

namespace Modules\Rbac\Livewire\Permission;

use App\Models\SysMenu;
use App\Models\SysPermission;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Modules\Rbac\Models\SysMenuAction;
use Modules\Rbac\Services\Actions\Permission\PermissionDestroy;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab = 'menu_actions'; // 'menu_actions' | 'standalone'
    public string $searchMenu = '';
    public string $searchPerm = '';

    #[Computed()]
    public function menus()
    {
        return SysMenu::with('actions')
            ->whereNotNull('permission_prefix')
            ->where('permission_prefix', '!=', '')
            ->when($this->searchMenu, fn($q) =>
                $q->where('label_name_en', 'like', '%' . $this->searchMenu . '%')
                  ->orWhere('permission_prefix', 'like', '%' . $this->searchMenu . '%')
            )
            ->orderBy('sort_num')
            ->get();
    }

    #[Computed()]
    public function standalonePermissions()
    {
        return SysPermission::query()
            ->when($this->searchPerm, fn($q) =>
                $q->where('name', 'like', '%' . $this->searchPerm . '%')
            )
            ->where('type', 'standalone')
            ->orderBy('group')
            ->paginate(10);
    }

    public function deletePermission(int $id): void
    {
        try {
            (new PermissionDestroy($id))->handle();
            $this->dispatch('close-modal-delete');
            flash()->success('Deleted permission successfully');
        } catch (\Throwable $err) {
            flash()->error($err->getMessage());
            Log::error($err->getMessage());
        }
    }

    public function deleteAction(int $id): void
    {
        try {
            $action = SysMenuAction::findOrFail($id);
            $label  = $action->label;
            $action->delete();

            $this->dispatch('close-modal-delete');
            flash()->success("Deleted action '{$label}' and its permission successfully");
        } catch (\Throwable $err) {
            flash()->error($err->getMessage());
            Log::error($err->getMessage());
        }
    }

    #[On('saved')]
    public function saved(): void
    {
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('rbac::livewire.permission.index');
    }
}
