<?php
/**
 * @var Router $router
 */

use Encoda\Activity\Http\Controllers\Admin\AdminActivityController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware'=> ['auth']] , function() {


    /** ================= ACTIVITIES ================ */
    Route::group( ['prefix' => '/organizations/{organizationUid}/divisions/{divisionUid}/business-units/{businessUnitUid}'], function() {
        Route::get('/activities', [
            'as' => 'activities.list',
            'uses' => AdminActivityController::class . '@index'
        ]);
    
        Route::get('/activities/{uid}', [
            'as' => 'activities.detail',
            'uses' => AdminActivityController::class . '@detail'
        ]);
    
        Route::post('/activities', [
            'as' => 'activities.create',
            'uses' => AdminActivityController::class . '@create'
        ]);
    
        Route::put('/activities/{uid}', [
            'as' => 'activities.update',
            'uses' => AdminActivityController::class . '@update'
        ]);
    
        Route::delete('/activities/{uid}', [
            'as' => 'activities.delete',
            'uses' => AdminActivityController::class . '@delete'
        ]);
    });
    
    Route::get('/organizations/{organizationUid}/activities', [
        'as' => 'organizations.activities',
        'uses' =>  AdminActivityController::class. '@getOrgActivities'
    ]);
    
    Route::get('/organizations/{organizationUid}/divisions/{divisionUid}/activities', [
        'as' => 'divisions.activities',
        'uses' =>  AdminActivityController::class. '@getDivisionActivities'
    ]);
    
});
