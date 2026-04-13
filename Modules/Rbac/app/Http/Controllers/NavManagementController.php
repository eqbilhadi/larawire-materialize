<?php

namespace Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SysMenu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NavManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:create menu', only: ['create']),
            new Middleware('permission:edit menu', only: ['edit']),
            new Middleware('permission:sort menu', only: ['sort']),
        ];
    }

    public function index()
    {
        return view('rbac::pages.navigation.index');
    }

    public function create()
    {
        return view('rbac::pages.navigation.form');
    }

    public function edit(SysMenu $sysMenu)
    {
        return view('rbac::pages.navigation.form', [
            "menu" => $sysMenu
        ]);
    }

    public function sort()
    {
        return view('rbac::pages.navigation.sort');
    }
}
