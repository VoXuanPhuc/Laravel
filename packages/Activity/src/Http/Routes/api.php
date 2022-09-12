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





});
