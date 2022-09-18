<?php

use Encoda\Activity\Http\Controllers\Tenant\ActivityController;
use Encoda\Activity\Http\Controllers\Tenant\ApplicationController;
use Encoda\Activity\Http\Controllers\Tenant\EquipmentController;
use Encoda\Activity\Http\Controllers\Tenant\RemoteAccessFactorController;
use Encoda\Activity\Http\Controllers\Tenant\UtilityController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware'=> ['auth']] , function() {


    Route::get('/activities', [
        'as' => 'activities.list',
        'uses' => ActivityController::class . '@index'
    ]);

    Route::get('/activities/{uid}', [
        'as' => 'activities.detail',
        'uses' => ActivityController::class . '@detail'
    ]);

    Route::post('/activities', [
        'as' => 'activities.create',
        'uses' => ActivityController::class . '@create'
    ]);

    Route::put('/activities/{uid}', [
        'as' => 'activities.update',
        'uses' => ActivityController::class . '@update'
    ]);

    Route::delete('/activities/{uid}', [
        'as' => 'activities.delete',
        'uses' => ActivityController::class . '@delete'
    ]);

    //======= ACTIVITY AND RELATIONSHIP =========/
    Route::put('/activities/{activityUid}/remote-access-factors', [
        'as' => 'activities.remote-access-factors-update',
        'uses' => ActivityController::class . '@saveRemoteAccessFactors'
    ]);

    Route::put('/activities/{activityUid}/applications-and-equipments', [
        'as' => 'activities.applications-and-equipments.update',
        'uses' => ActivityController::class . '@saveApplicationsAndEquipments'
    ]);


    // ============== UTILITIES ===============/
    Route::get('/utilities', [
        'as' => 'utilities.list',
        'uses' => UtilityController::class . '@index'
    ]);

    Route::get('/utilities/{uid}', [
        'as' => 'utilities.detail',
        'uses' => UtilityController::class . '@detail'
    ]);

    Route::post('/utilities', [
        'as' => 'utilities.create',
        'uses' => UtilityController::class . '@create'
    ]);

    Route::put('/utilities/{uid}', [
        'as' => 'utilities.update',
        'uses' => UtilityController::class . '@update'
    ]);

    Route::delete('/utilities/{uid}', [
        'as' => 'utilities.delete',
        'uses' => UtilityController::class . '@delete'
    ]);


    // ============= EQUIPMENT ============
    // =========== equipments =========/
    Route::get('/equipments', [
        'as' => 'equipments.list',
        'uses' => EquipmentController::class . '@index'
    ]);

    Route::get('/equipments/{uid}', [
        'as' => 'equipments.detail',
        'uses' => EquipmentController::class . '@detail'
    ]);

    Route::post('/equipments', [
        'as' => 'equipments.create',
        'uses' => EquipmentController::class . '@create'
    ]);

    Route::put('/equipments/{uid}', [
        'as' => 'equipments.update',
        'uses' => EquipmentController::class . '@update'
    ]);

    Route::delete('/equipments/{uid}', [
        'as' => 'equipments.delete',
        'uses' => EquipmentController::class . '@delete'
    ]);

    // =============== APPLICATION ================//
    // =========== applications =========/
    Route::get('/applications', [
        'as' => 'applications.list',
        'uses' => ApplicationController::class . '@index'
    ]);

    Route::get('/applications/{uid}', [
        'as' => 'applications.detail',
        'uses' => ApplicationController::class . '@detail'
    ]);

    Route::post('/applications', [
        'as' => 'applications.create',
        'uses' => ApplicationController::class . '@create'
    ]);

    Route::put('/applications/{uid}', [
        'as' => 'applications.update',
        'uses' => ApplicationController::class . '@update'
    ]);

    Route::delete('/applications/{uid}', [
        'as' => 'applications.delete',
        'uses' => ApplicationController::class . '@delete'
    ]);

    // =============== REMOTE ACCESS FACTOR ================//
    // =========== remote-access-factors =========/
    Route::get('/remote-access-factors', [
        'as' => 'remote-access-factors.list',
        'uses' => RemoteAccessFactorController::class . '@index'
    ]);

    Route::get('/remote-access-factors/{uid}', [
        'as' => 'remote-access-factors.detail',
        'uses' => RemoteAccessFactorController::class . '@detail'
    ]);

    Route::post('/remote-access-factors', [
        'as' => 'remote-access-factors.create',
        'uses' => RemoteAccessFactorController::class . '@create'
    ]);

    Route::put('/remote-access-factors/{uid}', [
        'as' => 'remote-access-factors.update',
        'uses' => RemoteAccessFactorController::class . '@update'
    ]);

    Route::delete('/remote-access-factors/{uid}', [
        'as' => 'remote-access-factors.delete',
        'uses' => RemoteAccessFactorController::class . '@delete'
    ]);
});
