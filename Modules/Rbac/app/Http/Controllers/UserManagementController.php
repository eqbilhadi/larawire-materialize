<?php

namespace Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SysUser;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rbac::pages.user.index');
    }

    public function create()
    {
        return view('rbac::pages.user.form');
    }

    public function edit(SysUser $sysUser)
    {
        return view('rbac::pages.user.form', [
            "user" => $sysUser
        ]);
    }
}
