<?php
/**
 * @var Router $router
 */

use Encoda\Auth\Http\Controllers\AuthChallengeController;
use Encoda\Auth\Http\Controllers\AuthController;
use Encoda\Auth\Http\Controllers\PasswordController;
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
    Route::post('forgot-password', [
        'as' => 'auth.forgot-password',
        'uses' =>  PasswordController::class. '@forgotPassword'
    ]);

    // Request to get code to change password
    Route::post('confirm-forgot-password', [
        'as' => 'auth.confirm-forgot-password',
        'uses' =>  PasswordController::class. '@confirmForgotPassword'
    ]);

    //Change password
    Route::post('change-password-challenge', [
        'as' => 'auth.change-password-challenge',
        'uses' =>  AuthChallengeController::class. '@respondForceChangePasswordChallenge'
    ]);
});
