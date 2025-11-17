<?php

namespace Modules\Rbac\Livewire\User;

use App\Models\SysUser;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Modules\Rbac\Services\Actions\User\UserDestroy;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = "bootstrap";

    public array $filter = [
        'search' => '',
        'gender' => '',
        'status' => ''
    ];

    #[Computed]
    public function lists()
    {
        return SysUser::latest()
            ->with('roles')
            ->when($this->filter['search'], function ($query) {
                $query->where('name', 'like', '%' . $this->filter['search'] . '%');
            })
            ->when($this->filter['gender'], fn($q, $gender) => $q->where('gender', $gender))
            ->when($this->filter['status'], fn($q, $status) => $q->where('status', $status))
            ->paginate(15);
    }

    public function delete($id)
    {
        try {
            (new UserDestroy($id))->handle();

            $this->dispatch('close-modal-delete');
            flash()->success('Deleted user successfully');
        } catch (\Throwable $err) {
            flash()->error($err->getMessage());
            Log::error($err->getMessage());
        }
    }

    public function render()
    {
        return view('rbac::livewire.user.index');
    }
}
