<?php

namespace Modules\Rbac\Livewire\Navigation;

use App\Jobs\ForgetCacheMenu;
use App\Models\SysMenu;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Rbac\Services\Actions\Navigation\NavigationDestroy;

class Index extends Component
{
    public array $filter = [
        'search' => '',
        'is_active' => ''
    ];

    #[Computed]
    public function lists()
    {
        return SysMenu::query()
            ->when($this->filter['search'], function ($query) {
                $query->where(function ($q) {
                    $q->where('label_name_en', 'like', '%' . $this->filter['search'] . '%')
                        ->orWhere('label_name_pt', 'like', '%' . $this->filter['search'] . '%')
                        ->orWhere('label_name_tl', 'like', '%' . $this->filter['search'] . '%');
                });
            })
            ->when($this->filter['is_active'] != '', function ($query) {
                $query->where('is_active', filter_var($this->filter['is_active'], FILTER_VALIDATE_BOOLEAN));
            })
            ->orderBy('sort_num')
            ->get();
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
