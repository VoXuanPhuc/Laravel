<?php

use Encoda\BCP\Http\Controllers\BCPController;
use Encoda\BCP\Http\Controllers\BCPLogController;
use Illuminate\Support\Facades\Route;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {

    /** DEPENDENCY SCENARIOS */
    /**
     * List
     */
    Route::group(['prefix' => 'bcps'], function() {
        Route::get('/', [
            'as' => 'bcp.list',
            'uses' =>  BCPController::class. '@index'
        ]);

        /**
         * Top
         */
        Route::get('/top', [
            'as' => 'bcp.top',
            'uses' =>  BCPController::class. '@top'
        ]);

        Route::post('/', [
            'as' => 'bcp.create',
            'uses' =>  BCPController::class. '@create'
        ]);


        Route::get('/{uid}', [
            'as' => 'bcp.detail',
            'uses' =>  BCPController::class. '@detail'
        ]);

        Route::get('/{uid}/export', [
            'as' => 'bcp.export',
            'uses' =>  BCPController::class. '@export'
        ]);

        Route::get('/{uid}/logs', [
            'as' => 'bcp.export',
            'uses' =>  BCPLogController::class. '@logs'
        ]);

        Route::put('/{uid}', [
            'as' => 'bcp.update',
            'uses' =>  BCPController::class. '@update'
        ]);

        Route::delete('/{uid}', [
            'as' => 'bcp.delete',
            'uses' =>  BCPController::class. '@delete'
        ]);


        Route::get('/download/all', [
            'as' => 'bcp.exportAll',
            'uses' =>  BCPController::class. '@exportAll'
        ]);

    });
});
