<?php

namespace Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SysRole;
use Illuminate\Http\Request;

class RoleManagementController extends Controller
{
    public function index()
    {
        return view('rbac::pages.role.index');
    }

   public function create()
    {
        return view('rbac::pages.role.form');
    }

    public function edit(SysRole $sysRole)
    {
        return view('rbac::pages.role.form', [
            "role" => $sysRole
        ]);
    }
}
