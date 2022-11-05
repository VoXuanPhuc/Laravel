<?php
/**
 * @var Router $router
 */


use Encoda\Notification\Http\Controllers\DashboardNotificationController;
use Encoda\Notification\Http\Controllers\EventNotificationController;
use Encoda\Notification\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group(['prefix' => '/identity/api/v1/', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => '/event-notifications'], function () {
        Route::get('/configs', [
            'as'   => 'event-notifications.configs',
            'uses' => EventNotificationController::class . '@configs'
        ]);
        Route::get('/', [
            'as'   => 'event-notifications.list',
            'uses' => EventNotificationController::class . '@list'
        ]);
        Route::get('/{uid}', [
            'as'   => 'event-notifications.detail',
            'uses' => EventNotificationController::class . '@detail'
        ]);
        Route::post('/', [
            'as'   => 'event-notifications.create',
            'uses' => EventNotificationController::class . '@create'
        ]);

        Route::put('/{uid}', [
            'as'   => 'event-notifications.update',
            'uses' => EventNotificationController::class . '@update'
        ]);

        Route::delete('/{uid}', [
            'as'   => 'event-notifications.delete',
            'uses' => EventNotificationController::class . '@delete'
        ]);

    });
    Route::group(['prefix' => '/notifications'], function () {

        Route::get('/', [
            'as'   => 'notifications.index',
            'uses' => NotificationController::class . '@index'
        ]);

        Route::patch( '/{uid}/mark-as-read', [
            'as' => 'notifications.read',
            'uses' =>  NotificationController::class. '@markAsRead'
        ] );

        // Notification to display on client dashboard
        Route::get('/dashboard', [
            'as'   => 'notifications.dashboard',
            'uses' => DashboardNotificationController::class . '@index'
        ]);

    });
});
