<?php

namespace Modules\Rbac\Livewire\Permission;

use App\Models\SysPermission;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Modules\Rbac\Services\Actions\Permission\PermissionDestroy;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public array $filter = [
        'search' => '',
    ];

    #[Computed()]
    public function lists()
    {
        return SysPermission::with('roles')
            ->when($this->filter['search'], function ($query) {
                $query->where('name', 'like', '%' . $this->filter['search'] . '%');
            })
            ->orderBy('group', 'asc')
            ->paginate(10);
    }

    public function delete($id)
    {
        try {
            (new PermissionDestroy($id))->handle();

            $this->dispatch('close-modal-delete');
            flash()->success('Deleted permission successfully');
        } catch (\Throwable $err) {
            flash()->error($err->getMessage());
            Log::info($err->getMessage());
        }
    }

    #[On('saved')]
    public function saved()
    {
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('rbac::livewire.permission.index');
    }
}
