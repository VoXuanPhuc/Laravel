<?php
/**
 * @var Router $router
 */

use Encoda\Rbac\Http\Controllers\PermissionController;
use Encoda\Rbac\Http\Controllers\PermissionGroupController;
use Encoda\Rbac\Http\Controllers\RoleController;
use Encoda\Rbac\Http\Controllers\RolePermissionController;
use Encoda\Rbac\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware'=> ['auth']] , function() {

    /** ========== USER ROLES ================= */
    Route::patch( '/users/{userUid}/roles', [
        'as' => 'roles.assign-role-to-user',
        'uses' => UserRoleController::class . '@assignRoleToUser'
    ]);


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
    // Get list permissions which associated with role by role uid
    Route::get('/roles/{uid}/permissions', [
        'as' => 'roles.list-associated-permission',
        'uses' => RolePermissionController::class . '@roleListAssociatedPermissions'
    ]);

    // Associate a permission to role
    Route::put( '/roles/{roleUid}/permissions', [
        'as' => 'roles.role-add-permission',
        'uses' => RolePermissionController::class . '@roleAddPermission'
    ] );

    // Remove associated a permission from role
    Route::delete( '/roles/{roleUid}/permissions/{permissionUid}', [
        'as' => 'roles.role-remove-permission',
        'uses' => RolePermissionController::class . '@roleRemovePermission'
    ] );

    // Sync permissions to role with a list of permissions
    Route::patch('/roles/{roleUid}/permissions', [
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
