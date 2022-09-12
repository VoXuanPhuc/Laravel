<?php
/**
 * @var Router $router
 */

use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;
use Encoda\Activity\Http\Controllers\ActivityController;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware'=> ['auth']] , function() {


    /** ================= ACTIVITIES ================ */
    Route::get('/admin/activities', [
        'as' => 'activities.list',
        'uses' => ActivityController::class . '@list'
    ]);
    
    Route::get('/admin/activities/{uid}', [
        'as' => 'activities.show',
        'uses' => ActivityController::class . '@show'
    ]);
    
    Route::post('/admin/activities', [
        'as' => 'activities.store',
        'uses' => ActivityController::class . '@store'
    ]);
    
    Route::put('/admin/activities/{uid}', [
        'as' => 'activities.update',
        'uses' => ActivityController::class . '@update'
    ]);
    
});
