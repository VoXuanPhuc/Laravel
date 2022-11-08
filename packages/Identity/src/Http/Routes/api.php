<?php
/**
 * @var Router $router
 */

use Encoda\Identity\Http\Controllers\UserController;
use Encoda\Identity\Http\Controllers\UserGroupController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {


    /** ======== User routing  */
    Route::get('/users', [
        'as' => 'users.list',
        'uses' =>  UserController::class. '@list'
    ]);

    Route::get('/users/{id}', [
        'as' => 'users.detail',
        'uses' =>  UserController::class. '@detail'
    ]);

    //Create user
    Route::post('/users', [
        'as' => 'users.create',
        'uses' =>  UserController::class. '@create'
    ]);

    //Create confirm signup
    Route::post('/users/confirmation', [
        'as' => 'users.confirm-signup',
        'uses' =>  UserController::class. '@confirmSignup'
    ]);

    //Update user
    Route::put('/users/{id}', [
        'as' => 'users.update',
        'uses' =>  UserController::class. '@update'
    ]);

    //Reinvite user
    Route::post('/users/{id}/reinvite', [
        'as' => 'users.reinvite',
        'uses' =>  UserController::class. '@reinvite'
    ]);

    //Delete group
    Route::delete( '/users/{id}', [
        'as' => 'users.delete',
        'uses' =>  UserController::class. '@delete'
    ]);


    /** ======== User group routing  */
    //Get group
    Route::get('/groups/{id}', [
        'as' => 'user-groups.detail',
        'uses' =>  UserGroupController::class. '@detail'
    ]);

    //Get list group
    Route::get('/groups', [
        'as' => 'user-groups.list',
        'uses' =>  UserGroupController::class. '@list'
    ]);


    //Create group
    Route::post('/groups', [
        'as' => 'user-groups.create',
        'uses' =>  UserGroupController::class. '@create'
    ]);

    //Update group
    Route::put('/groups/{id}', [
        'as' => 'user-groups.update',
        'uses' =>  UserGroupController::class. '@update'
    ]);

    //Delete group
    Route::delete( '/groups/{id}', [
        'as' => 'user-groups.delete',
        'uses' =>  UserGroupController::class. '@delete'
    ]);

});
