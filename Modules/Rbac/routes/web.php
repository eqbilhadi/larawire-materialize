<?php

use Illuminate\Support\Facades\Route;
use Modules\Rbac\Http\Controllers\NavManagementController;
use Modules\Rbac\Http\Controllers\PermissionManagementController;
use Modules\Rbac\Http\Controllers\RoleManagementController;
use Modules\Rbac\Http\Controllers\UserManagementController;

Route::group(['middleware' => ['auth', 'checkAccess'], 'prefix' => 'rbac', 'as' => 'rbac.'], function () {
    Route::resource('navigation-management', NavManagementController::class)
        ->except('show')
        ->names('nav')
        ->parameters([
            'navigation-management' => 'sysMenu'
        ])
        ->whereNumber('sysMenu');

    Route::get('navigation-management/sort', [NavManagementController::class, 'sort'])
        ->name('nav.sort');

    Route::post('navigation-management/sort', [NavManagementController::class, 'sortUpdate'])
        ->name('nav.sort-update');

    Route::resource('permission-management', PermissionManagementController::class)
        ->except(['show', 'create', 'edit'])
        ->names('permission')
        ->parameters([
            'permission-management' => 'sysPermission'
        ])
        ->whereNumber('sysPermission');

    Route::resource('role-management', RoleManagementController::class)
        ->except('show')
        ->names('role')
        ->parameters([
            'role-management' => 'sysRole'
        ])
        ->whereNumber('sysRole');

    Route::resource('user-management', UserManagementController::class)
        ->except('show')
        ->names('user')
        ->parameters([
            'user-management' => 'sysUser'
        ])
        ->whereNumber('sysUser');
});
