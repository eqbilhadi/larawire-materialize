<?php

namespace Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionManagementController extends Controller
{
    public function index()
    {
        return view('rbac::pages.permission.index');
    }
}
