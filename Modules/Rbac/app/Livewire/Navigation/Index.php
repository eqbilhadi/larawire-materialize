<?php

namespace Modules\Rbac\Livewire\Navigation;

use App\Jobs\ForgetCacheMenu;
use App\Models\SysMenu;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Modules\Rbac\Services\Actions\Navigation\NavigationDestroy;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public array $filter = [
        'search' => '',
        'is_active' => ''
    ];

    #[Computed]
    public function lists()
    {
        return SysMenu::query()
            ->select('sys_menus.*')
            ->leftJoin('sys_menus as parent', 'sys_menus.parent_id', '=', 'parent.id')
            ->when($this->filter['search'], function ($query) {
                $query->where(function ($q) {
                    $q->where('sys_menus.label_name_en', 'like', '%' . $this->filter['search'] . '%')
                    ->orWhere('sys_menus.label_name_pt', 'like', '%' . $this->filter['search'] . '%')
                    ->orWhere('sys_menus.label_name_tl', 'like', '%' . $this->filter['search'] . '%');
                });
            })
            ->when($this->filter['is_active'] != '', function ($query) {
                $query->where('sys_menus.is_active', filter_var($this->filter['is_active'], FILTER_VALIDATE_BOOLEAN));
            })
            ->orderByRaw('COALESCE(parent.sort_num, sys_menus.sort_num) ASC')
            ->orderByRaw('CASE WHEN sys_menus.parent_id IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('sys_menus.sort_num', 'ASC')
            ->paginate(10);
    }

    public function delete($id)
    {
        try {
            (new NavigationDestroy($id))->handle();
            dispatch(new ForgetCacheMenu());

            $this->dispatch('close-modal-delete');
            flash()->success('Deleted navigation successfully');
        } catch (\Throwable $err) {
            flash()->error($err->getMessage());
            Log::info($err->getMessage());
        }
    }

    public function render()
    {
        return view('rbac::livewire.navigation.index');
    }
}
