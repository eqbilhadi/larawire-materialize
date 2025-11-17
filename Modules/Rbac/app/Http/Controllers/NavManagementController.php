<?php

namespace Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SysMenu;
use Illuminate\Http\Request;

class NavManagementController extends Controller
{
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
