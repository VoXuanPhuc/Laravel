<?php
/**
 * @var Router $router
 */


use Encoda\Task\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/admin', 'middleware' => 'auth'] , function() {


    /** ======== Statistic routing  */
    Route::get('/tasks', [
        'as' => 'tenant.tasks',
        'uses' =>  TaskController::class. '@index'
    ]);

});
