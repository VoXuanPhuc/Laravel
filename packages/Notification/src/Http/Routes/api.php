<?php
/**
 * @var Router $router
 */


use Encoda\Notification\Http\Controllers\DashboardNotificationController;
use Encoda\Notification\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {

    Route::group( ['prefix' => '/notifications'], function() {

        Route::get( '/', [
            'as' => 'notifications.index',
            'uses' =>  NotificationController::class. '@index'
        ] );

        Route::patch( '/{uid}/mark-as-read', [
            'as' => 'notifications.read',
            'uses' =>  NotificationController::class. '@markAsRead'
        ] );

        // Notification to display on client dashboard
        Route::get( '/dashboard', [
            'as' => 'notifications.dashboard',
            'uses' =>  DashboardNotificationController::class. '@index'
        ] );

    } );
});
