<?php

namespace Modules\Rbac\Livewire\Role;

use App\Models\SysRole;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Modules\Rbac\Services\Actions\Role\RoleDestroy;

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
        return SysRole::latest()
            ->when($this->filter['search'], function ($query) {
                $query->where('name', 'like', '%' . $this->filter['search'] . '%');
            })
            ->paginate(10);
    }

    public function delete($id)
    {
        try {
            (new RoleDestroy($id))->handle();

            $this->dispatch('close-modal-delete');
            flash()->success('Deleted permission successfully');
        } catch (\Throwable $err) {
            flash()->error('Something went wrong, try again later!');
            Log::info($err->getMessage());
        }
    }

    public function render()
    {
        return view('rbac::livewire.role.index');
    }
}
