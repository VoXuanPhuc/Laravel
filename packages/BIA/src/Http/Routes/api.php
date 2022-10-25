<?php

use Encoda\BIA\Http\Controllers\BIAController;
use Illuminate\Support\Facades\Route;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {

    /** DEPENDENCY SCENARIOS */
    Route::group(['prefix' => 'bias'], function() {
        Route::get('/', [
            'as' => 'bia.list',
            'uses' =>  BIAController::class. '@index'
        ]);

        Route::post('/', [
            'as' => 'bia.create',
            'uses' =>  BIAController::class. '@create'
        ]);


        Route::get('/{uid}', [
            'as' => 'bia.detail',
            'uses' =>  BIAController::class. '@detail'
        ]);

        Route::put('/{uid}', [
            'as' => 'bia.update',
            'uses' =>  BIAController::class. '@update'
        ]);

        Route::delete('/{uid}', [
            'as' => 'bia.delete',
            'uses' =>  BIAController::class. '@delete'
        ]);
    });
});
