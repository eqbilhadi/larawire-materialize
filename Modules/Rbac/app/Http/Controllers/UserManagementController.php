<?php

namespace Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SysUser;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:create user', only: ['create']),
            new Middleware('permission:edit user', only: ['edit']),
        ];
    }

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
