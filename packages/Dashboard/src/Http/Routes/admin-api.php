<?php
/**
 * @var Router $router
 */


use Encoda\Dashboard\Http\Controllers\Admin\SystemStatisticController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/admin', 'middleware' => 'auth'] , function() {


    /** ======== Statistic routing  */
    Route::get('/system-statistics', [
        'as' => 'system.statistics',
        'uses' =>  SystemStatisticController::class. '@systemStatistics'
    ]);

});
