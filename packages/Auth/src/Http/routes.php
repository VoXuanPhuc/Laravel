<?php
/**
 * @var Router $router
 */

use Encoda\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/v1/identity'] , function() {

    //Login
    Route::post('login', [
        'as' => 'auth.login',
        'uses' =>  AuthController::class. '@login'
    ]);

    //Login
    Route::post('signup', [
        'as' => 'auth.signup',
        'uses' =>  AuthController::class. '@signup'
    ]);

    //Profile
    Route::get('profile', [
        'as' => 'auth.profile',
        'uses' =>  AuthController::class. '@profile'
    ]);


});
