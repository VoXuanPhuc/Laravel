<?php

use Encoda\BIA\Http\Controllers\BIAController;
use Encoda\BIA\Http\Controllers\BIALogController;
use Illuminate\Support\Facades\Route;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware'=> ['auth'] ] , function() {

    /** DEPENDENCY SCENARIOS */
    Route::group(['prefix' => 'bias'], function() {

        /**
         * Get list BIAs
         */
        Route::get('/', [
            'as' => 'bia.list',
            'uses' =>  BIAController::class. '@index'
        ]);

        /**
         * Get top BIAs
         */
        Route::get('/top', [
            'as' => 'bia.top',
            'uses' =>  BIAController::class. '@top'
        ]);

        /**
         * Create a BIA
         */
        Route::post('/', [
            'as' => 'bia.create',
            'uses' =>  BIAController::class. '@create'
        ]);


        /**
         * Get a BIA
         */
        Route::get('/{uid}', [
            'as' => 'bia.detail',
            'uses' =>  BIAController::class. '@detail'
        ]);

        /**
         * Update a BIA
         */
        Route::put('/{uid}', [
            'as' => 'bia.update',
            'uses' =>  BIAController::class. '@update'
        ]);

        /**
         * Export a BIA
         */
        Route::get('/{uid}/export', [
            'as' => 'bia.export',
            'uses' =>  BIAController::class. '@export'
        ]);

        /**
         * Get BIA Logs
         */
        Route::get('/{uid}/logs', [
            'as' => 'bia.logs',
            'uses' =>  BIALogController::class. '@logs'
        ]);

        /**
         * Delete a specific BIA
         */
        Route::delete('/{uid}', [
            'as' => 'bia.delete',
            'uses' =>  BIAController::class. '@delete'
        ]);

        /**
         * Download All
         */
        Route::get('/download/all', [
            'as' => 'bia.download-all',
            'uses' =>  BIAController::class. '@exportAll'
        ]);
    });
});
