<?php
/**
 * @var Router $router
 */

use Encoda\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/'] , function() {

    /** ======== Auth routing  */
    //Login
    Route::post('login', [
        'as' => 'auth.login',
        'uses' =>  AuthController::class. '@login'
    ]);

    //Signup
    Route::post('signup', [
        'as' => 'auth.signup',
        'uses' =>  AuthController::class. '@signup'
    ]);


    //Change password
    Route::post('change-password', [
        'as' => 'auth.change-password',
        'uses' =>  AuthController::class. '@respondForceChangePasswordChallenge'
    ]);
});
