<?php

namespace Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SysRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:create role', only: ['create']),
            new Middleware('permission:edit role', only: ['edit']),
        ];
    }
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
