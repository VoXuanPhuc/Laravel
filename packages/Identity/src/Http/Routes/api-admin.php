<?php

use Encoda\Identity\Http\Controllers\AdminClientController;
use Encoda\Identity\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::group( ['prefix' => '/identity/api/v1/'] , function() {


    /** ========= Clients ===== */
    Route::post('/admin/clients', [
        'as' => 'admin.client.create',
        'uses' => AdminClientController::class . '@createClient'
    ]);

    Route::put('/admin/clients', [
        'as' => 'admin.client.update',
        'uses' => AdminClientController::class . '@updateClient'
    ]);

    Route::get('/admin/clients', [
        'as' => 'admin.client.list',
        'uses' => AdminClientController::class . '@list'
    ]);

    Route::get('/admin/clients/{id}', [
        'as' => 'admin.client.detail',
        'uses' => AdminClientController::class . '@detail'
    ]);


    /**======= Users ======*/
    Route::post('/admin/user/confirmation', [
        'as' => 'admin.user.confirmation',
        'uses' => AdminController::class . '@confirmSignup'
    ]);

    Route::post('/admin/user/enable', [
        'as' => 'admin.user.enable',
        'uses' => AdminController::class . '@enableUser'
    ]);

    Route::post('/admin/user/disable', [
        'as' => 'admin.user.disable',
        'uses' => AdminController::class . '@disableUser'
    ]);

    //List user group
    Route::get('/admin/user/{username}/groups', [
        'as' => 'admin.user.groups',
        'uses' => AdminController::class . '@listUserGroups'
    ]);


    /* ============= Group ==*/
    Route::put('/admin/groups/{groupId}', [
        'as' => 'admin.group.add-user',
        'uses' => AdminController::class . '@addUserToGroup'
    ]);

    Route::delete('/admin/groups/{groupId}', [
        'as' => 'admin.group.remove-user',
        'uses' => AdminController::class . '@removeUserFromGroup'
    ]);

});
