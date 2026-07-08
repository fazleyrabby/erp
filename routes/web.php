<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RolePermission\PermissionController;
use App\Http\Controllers\Admin\RolePermission\PermissionToRoleController;
use App\Http\Controllers\Admin\RolePermission\RoleController;
use App\Http\Controllers\Admin\Setup\CompanySettingController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['middleware' => ['auth']], function () {

    /*  Company Settings Routes */
    Route::name('company.')->prefix('company')->group(function () {
        Route::get('/settings/view', [CompanySettingController::class, 'index'])->name('settings.view');
        Route::get('/settings/edit', [CompanySettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings/update', [CompanySettingController::class, 'update'])->name('settings.update');
    });
    /*  Users Routes */
    Route::name('users.')->prefix('users')->group(function () {
        Route::get('/view', [UserController::class, 'index'])->name('');
        Route::get('/usertype-view', [UserController::class, 'usertypeIndex'])->name('usertype.view');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit', [UserController::class, 'edit'])->name('edit');
        Route::post('/update', [UserController::class, 'update'])->name('update');
        Route::post('/delete', [UserController::class, 'delete'])->name('delete');
    });

    Route::post('changePassword', [UserController::class, 'changePassword'])->name('changePassword');

    /* Roles */
    Route::get('role/view', [RoleController::class, 'index'])->name('rolesView');
    Route::post('roles/store', [RoleController::class, 'store'])->name('roleStore');
    Route::get('roles/delete/{id}', [RoleController::class, 'delete'])->name('roleDelete');
    /* Permissions */
    Route::get('permission/view', [PermissionController::class, 'index'])->name('permissionView');
    Route::post('permission/store', [PermissionController::class, 'store'])->name('permissionStore');
    Route::get('permission/edit', [PermissionController::class, 'edit'])->name('editPermission');
    Route::post('permission/update', [PermissionController::class, 'update'])->name('permissionUpdate');
    Route::get('permissions/delete/{id}', [PermissionController::class, 'delete'])->name('permissionDelete');

    /* Permission to role */
    Route::get('permission/to/role/view', [PermissionToRoleController::class, 'index'])->name('permissionToRoleList');
    // Route::get('/give/permission', [PermissionToRoleController::class, 'givePermission'])->name('givePermission')->middleware('permission:user.view');
    Route::get('/get/permission', [PermissionToRoleController::class, 'getPermission'])->name('getPermissions');
    Route::post('/give/permission/store', [PermissionToRoleController::class, 'store'])->name('roleToPermissionStore');
});
