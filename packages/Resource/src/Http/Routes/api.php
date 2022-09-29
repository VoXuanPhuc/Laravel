<?php
/**
 * @var Router $router
 */

use Encoda\Resource\Http\Controllers\ResourceCategoryController;
use Encoda\Resource\Http\Controllers\ResourceController;
use Encoda\Resource\Http\Controllers\ResourceOwnerController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {


    /** ======== Resource routing  */

    // List resource
    Route::get('/resources', [
        'as' => 'resources.list',
        'uses' =>  ResourceController::class. '@index'
    ]);

    // Get resource
    Route::get('/resources/{uid}', [
        'as' => 'resources.detail',
        'uses' =>  ResourceController::class. '@detail'
    ]);
    // Download resources
    Route::get('/resources/download/all', [
        'as' => 'resources.download',
        'uses' =>  ResourceController::class. '@download'
    ]);

    // Updates resource
    Route::put('/resources/{uid}', [
        'as' => 'resources.update',
        'uses' =>  ResourceController::class. '@update'
    ]);

    // Delete resource
    Route::delete('/resources/{uid}', [
        'as' => 'resources.delete',
        'uses' =>  ResourceController::class. '@delete'
    ]);

    // Update resource
    Route::post('/resources', [
        'as' => 'resources.create',
        'uses' =>  ResourceController::class. '@create'
    ]);

    /** ======== Resource owner routing  */
    Route::get('/resource-owners', [
        'as' => 'resource-owners.list',
        'uses' =>  ResourceOwnerController::class. '@index'
    ]);

    // Get all owner in org
    Route::get('/resource-owners/all', [
        'as' => 'resource-owners.list',
        'uses' =>  ResourceOwnerController::class. '@all'
    ]);

    // Get resource owner
    Route::get('/resource-owners/{uid}', [
        'as' => 'resource-owners.detail',
        'uses' =>  ResourceOwnerController::class. '@detail'
    ]);

    //Create resource owner
    Route::post('/resource-owners', [
        'as' => 'resource-owners.create',
        'uses' =>  ResourceOwnerController::class. '@create'
    ]);


    // Updates resource owner
    Route::put('/resource-owners/{uid}', [
        'as' => 'resource-owners.update',
        'uses' =>  ResourceOwnerController::class. '@update'
    ]);

    // Delete resource
    Route::delete('/resource-owners/{uid}', [
        'as' => 'resource-owners.delete',
        'uses' =>  ResourceOwnerController::class. '@delete'
    ]);


    /** ======== Resource category routing  */

    // Get list resource categories
    Route::get('/resource-categories', [
        'as' => 'resource-categories.list',
        'uses' =>  ResourceCategoryController::class. '@index'
    ]);

    // Get resource categories
    Route::get('/resource-categories/{uid}', [
        'as' => 'resource-categories.detail',
        'uses' =>  ResourceCategoryController::class. '@detail'
    ]);

    //Create resource categories
    Route::post('/resource-categories', [
        'as' => 'resource-categories.create',
        'uses' =>  ResourceCategoryController::class. '@create'
    ]);


    // Updates resource categories
    Route::put('/resource-categories/{uid}', [
        'as' => 'resource-categories.update',
        'uses' =>  ResourceCategoryController::class. '@update'
    ]);

    // Delete resource categories
    Route::delete('/resource-categories/{uid}', [
        'as' => 'resource-categories.delete',
        'uses' =>  ResourceCategoryController::class. '@delete'
    ]);
});
