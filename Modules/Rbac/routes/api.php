<?php

use Illuminate\Support\Facades\Route;
use Modules\Rbac\Http\Controllers\RbacController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('rbacs', RbacController::class)->names('rbac');
});
