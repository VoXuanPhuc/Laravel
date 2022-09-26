<?php
/**
 * @var Router $router
 */


use Encoda\Dashboard\Http\Controllers\Tenant\TenantStatisticController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {
    /** ======== Statistic routing  */
    Route::get('/system-statistics', [
        'as' => 'system.statistics',
        'uses' =>  TenantStatisticController::class. '@systemStatistics'
    ]);
});
