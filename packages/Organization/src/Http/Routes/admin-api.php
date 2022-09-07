<?php
/**
 * @var Router $router
 */


use Encoda\Organization\Http\Controllers\Admin\AdminBusinessUnitController;
use Encoda\Organization\Http\Controllers\Admin\AdminDivisionController;

use Encoda\Organization\Http\Controllers\Admin\IndustryController;
use Encoda\Organization\Http\Controllers\Admin\OrganizationController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/admin', 'middleware' => 'auth'] , function() {


    /** ======== Organization routing  */

    Route::get('/organizations', [
        'as' => 'organizations.list',
        'uses' =>  OrganizationController::class. '@index'
    ]);

    Route::post('/organizations', [
        'as' => 'organizations.create',
        'uses' =>  OrganizationController::class. '@create'
    ]);

    Route::get('/organizations/{uid}', [
        'as' => 'organizations.detail',
        'uses' =>  OrganizationController::class. '@detail'
    ]);

    Route::put('/organizations/{uid}', [
        'as' => 'organizations.update',
        'uses' =>  OrganizationController::class. '@update'
    ]);

    Route::delete('/organizations/{uid}', [
        'as' => 'organizations.delete',
        'uses' =>  OrganizationController::class. '@delete'
    ]);

    Route::post('/organizations/logo', [
        'as' => 'organizations.logo.upload',
        'uses' =>  OrganizationController::class. '@uploadLogo'
    ]);


    /** =========== Admin Division routing ====== */

    Route::group(['prefix' => '/organizations/{organizationUid}/'], function() {

        Route::get('/divisions', [
            'as' => 'divisions.list',
            'uses' =>  AdminDivisionController::class. '@index'
        ]);

        Route::post('/divisions', [
            'as' => 'divisions.create',
            'uses' =>  AdminDivisionController::class. '@create'
        ]);

        Route::get('/divisions/{uid}', [
            'as' => 'divisions.detail',
            'uses' =>  AdminDivisionController::class. '@detail'
        ]);

        Route::put('/divisions/{uid}', [
            'as' => 'divisions.update',
            'uses' =>  AdminDivisionController::class. '@update'
        ]);

        Route::delete('/divisions/{uid}', [
            'as' => 'divisions.delete',
            'uses' =>  AdminDivisionController::class. '@delete'
        ]);

    });


    /** =========== Admin Business Unit routing ====== */

    Route::get('/organizations/{organizationUid}/business-units', [
        'as' => 'organizations.business-units',
        'uses' =>  AdminBusinessUnitController::class. '@getOrgBusinessUnits'
    ]);

    Route::group( ['prefix' => '/organizations/{organizationUid}/divisions/{divisionUid}'], function() {

        Route::get('/business-units', [
            'as' => 'business-units.list',
            'uses' =>  AdminBusinessUnitController::class. '@index'
        ]);

        Route::post('/business-units', [
            'as' => 'business-units.create',
            'uses' =>  AdminBusinessUnitController::class. '@create'
        ]);

        Route::get('/business-units/{uid}', [
            'as' => 'business-units.detail',
            'uses' =>  AdminBusinessUnitController::class. '@detail'
        ]);

        Route::put('/business-units/{uid}', [
            'as' => 'business-units.update',
            'uses' =>  AdminBusinessUnitController::class. '@update'
        ]);

        Route::delete('/business-units/{uid}', [
            'as' => 'business-units.delete',
            'uses' =>  AdminBusinessUnitController::class. '@delete'
        ]);
    });


    // ========== Industries =====
    Route::get('/industries', [
        'as' => 'industries.list',
        'uses' =>  IndustryController::class. '@index'
    ]);

    Route::post('/industries', [
        'as' => 'industries.create',
        'uses' =>  IndustryController::class. '@create'
    ]);

    Route::put('/industries/{uid}', [
        'as' => 'industries.update',
        'uses' =>  IndustryController::class. '@update'
    ]);

    Route::delete('/industries/{uid}', [
        'as' => 'industries.delete',
        'uses' =>  IndustryController::class. '@delete'
    ]);

});
