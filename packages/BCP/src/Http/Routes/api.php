<?php

use Encoda\BCP\Http\Controllers\BCPController;
use Illuminate\Support\Facades\Route;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {

    /** DEPENDENCY SCENARIOS */
    Route::group(['prefix' => 'bcp'], function() {
        Route::get('/', [
            'as' => 'bcp.list',
            'uses' =>  BCPController::class. '@index'
        ]);

        Route::post('/', [
            'as' => 'bcp.create',
            'uses' =>  BCPController::class. '@create'
        ]);


        Route::get('/{uid}', [
            'as' => 'bcp.detail',
            'uses' =>  BCPController::class. '@detail'
        ]);

        Route::put('/{uid}', [
            'as' => 'bcp.update',
            'uses' =>  BCPController::class. '@update'
        ]);

        Route::delete('/{uid}', [
            'as' => 'bcp.delete',
            'uses' =>  BCPController::class. '@delete'
        ]);
    });
});
