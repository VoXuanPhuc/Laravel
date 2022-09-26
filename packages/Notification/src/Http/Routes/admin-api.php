<?php
/**
 * @var Router $router
 */


use Encoda\Notification\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/admin', 'middleware' => 'auth'] , function() {


    /** ======== Statistic routing  */
    Route::get('/notifications', [
        'as' => 'tenant.notifications',
        'uses' =>  NotificationController::class. '@index'
    ]);

});
