<?php
/**
 * @var Router $router
 */


use Encoda\Organization\Http\Controllers\Tenant\BusinessUnitController;
use Encoda\Organization\Http\Controllers\Tenant\DivisionController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => ['auth:api']] , function() {


    /** =========== Division routing ====== */

    Route::get('/divisions', [
        'as' => 'divisions.list',
        'uses' =>  DivisionController::class. '@index'
    ]);

    Route::post('/divisions', [
        'as' => 'divisions.create',
        'uses' =>  DivisionController::class. '@create'
    ]);

    Route::get('/divisions/{uid}', [
        'as' => 'divisions.detail',
        'uses' =>  DivisionController::class. '@detail'
    ]);

    Route::put('/divisions/{uid}', [
        'as' => 'divisions.update',
        'uses' =>  DivisionController::class. '@update'
    ]);

    Route::delete('/divisions/{uid}', [
        'as' => 'divisions.delete',
        'uses' =>  DivisionController::class. '@delete'
    ]);


    /** =========== Business Unit routing ====== */
    Route::get('/business-units', [
        'as' => 'business-units.list',
        'uses' =>  BusinessUnitController::class. '@businessUnitByOrg'
    ]);

    Route::group(['prefix' => 'divisions/{divisionUid}'], function() {

        Route::get('/business-units', [
            'as' => 'business-units.list',
            'uses' =>  BusinessUnitController::class. '@index'
        ]);

        Route::post('/business-units', [
            'as' => 'business-units.create',
            'uses' =>  BusinessUnitController::class. '@create'
        ]);

        Route::get('/business-units/{uid}', [
            'as' => 'business-units.detail',
            'uses' =>  BusinessUnitController::class. '@detail'
        ]);

        Route::put('/business-units/{uid}', [
            'as' => 'business-units.update',
            'uses' =>  BusinessUnitController::class. '@update'
        ]);

        Route::delete('/business-units/{uid}', [
            'as' => 'business-units.delete',
            'uses' =>  BusinessUnitController::class. '@delete'
        ]);

    });

});
