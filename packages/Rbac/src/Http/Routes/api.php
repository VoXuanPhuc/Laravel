<?php
/**
 * @var Router $router
 */

use Encoda\Rbac\Http\Controllers\PermissionController;
use Encoda\Rbac\Http\Controllers\PermissionGroupController;
use Encoda\Rbac\Http\Controllers\RoleController;
use Encoda\Rbac\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/'] , function() {

    /** ================= ROLES================ */
    Route::get('/roles', [
        'as' => 'roles.list',
        'uses' => RoleController::class . '@list'
    ]);

    Route::get('/roles/{uid}', [
        'as' => 'roles.detail',
        'uses' => RoleController::class . '@detail'
    ]);

    Route::post('/roles', [
        'as' => 'roles.create',
        'uses' => RoleController::class . '@create'
    ]);

    Route::put('/roles/{uid}', [
        'as' => 'roles.update',
        'uses' => RoleController::class . '@update'
    ]);

    Route::delete('/roles/{uid}', [
        'as' => 'roles.delete',
        'uses' => RoleController::class . '@delete'
    ]);

    /** =========== Role Permission ======== */
    Route::get('/roles/{uid}/permissions', [
        'as' => 'roles.list-associated-permission',
        'uses' => RolePermissionController::class . '@roleListAssociatedPermissions'
    ]);

    Route::put('/roles/{roleUid}/permissions', [
        'as' => 'roles.sync-permissions',
        'uses' => RolePermissionController::class . '@roleSyncPermissions'
    ]);

    /** ========= PERMISSION GROUP ========= */
    /** =================Permission================ */
    Route::get('/permissions-by-group', [
        'as' => 'permission-group.list-permission',
        'uses' => PermissionGroupController::class . '@listPermissionByGroup'
    ]);

    /** ================= PERMISSIONS ================ */
    Route::get('/permissions', [
        'as' => 'permissions.list',
        'uses' => PermissionController::class . '@list'
    ]);

    Route::get('/permissions/{uid}', [
        'as' => 'permissions.detail',
        'uses' => PermissionController::class . '@detail'
    ]);

    Route::post('/permissions', [
        'as' => 'permissions.create',
        'uses' => PermissionController::class . '@create'
    ]);

    Route::put('/permissions/{uid}', [
        'as' => 'permissions.update',
        'uses' => PermissionController::class . '@update'
    ]);

    Route::delete('/permissions/{uid}', [
        'as' => 'permissions.delete',
        'uses' => PermissionController::class . '@delete'
    ]);

});
