<?php
/**
 * @var Router $router
 */

use Encoda\Rbac\Http\Controllers\PermissionController;
use Encoda\Rbac\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/'] , function() {

    /** =================Role================ */
    Route::get('/roles', [
        'as' => 'roles.list',
        'uses' => RoleController::class . '@list'
    ]);

    Route::get('/roles/{id}', [
        'as' => 'roles.details',
        'uses' => RoleController::class . '@find'
    ]);

    Route::post('/roles', [
        'as' => 'roles.create',
        'uses' => RoleController::class . '@create'
    ]);

    Route::put('/roles/{id}', [
        'as' => 'roles.update',
        'uses' => RoleController::class . '@update'
    ]);

    Route::delete('/roles/{id}', [
        'as' => 'roles.delete',
        'uses' => RoleController::class . '@delete'
    ]);

    Route::post('/roles-permissions', [
        'as' => 'roles-permissions.create',
        'uses' => RoleController::class . '@createRolePermission'
    ]);

    /** =========== Role Permission ======== */
    Route::post('/roles/{id}/permissions-creation', [
        'as' => 'roles-permissions.create',
        'uses' => RoleController::class . '@createRolePermission'
    ]);

    Route::post('/roles/{id}/permissions-check', [
        'as' => 'roles-permissions.check',
        'uses' => RoleController::class . '@checkRolePermission'
    ]);

    Route::post('/roles/{id}/permissions-updation', [
        'as' => 'roles-permissions.update',
        'uses' => RoleController::class . '@updateRolePermission'
    ]);

    Route::post('/roles/{id}/permissions-deletion', [
        'as' => 'roles-permissions.delete',
        'uses' => RoleController::class . '@removeRolePermission'
    ]);

    /** =================Permission================ */
    Route::get('/permissions', [
        'as' => 'permissions.list',
        'uses' => PermissionController::class . '@list'
    ]);

    Route::get('/permissions/{id}', [
        'as' => 'permissions.details',
        'uses' => PermissionController::class . '@find'
    ]);

    Route::post('/permissions', [
        'as' => 'permissions.create',
        'uses' => PermissionController::class . '@create'
    ]);

    Route::put('/permissions/{id}', [
        'as' => 'permissions.update',
        'uses' => PermissionController::class . '@update'
    ]);

    Route::delete('/permissions/{id}', [
        'as' => 'permissions.delete',
        'uses' => PermissionController::class . '@delete'
    ]);

});
