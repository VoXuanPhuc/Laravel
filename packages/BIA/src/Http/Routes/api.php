<?php

use Encoda\BIA\Http\Controllers\BIAController;
use Encoda\BIA\Http\Controllers\BIALogController;
use Illuminate\Support\Facades\Route;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware'=> ['auth'] ] , function() {

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

        Route::get('/{uid}/export', [
            'as' => 'bia.export',
            'uses' =>  BIAController::class. '@export'
        ]);

        Route::get('/{uid}/logs', [
            'as' => 'bia.logs',
            'uses' =>  BIALogController::class. '@logs'
        ]);

        Route::delete('/{uid}', [
            'as' => 'bia.delete',
            'uses' =>  BIAController::class. '@delete'
        ]);

        Route::get('/download/all', [
            'as' => 'bia.download-all',
            'uses' =>  BIAController::class. '@exportAll'
        ]);
    });
});
