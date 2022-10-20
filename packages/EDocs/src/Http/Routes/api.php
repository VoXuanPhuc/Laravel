<?php

use Encoda\EDocs\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/identity/api/v1', 'middleware' => 'auth' ], function(){

    Route::group( ['prefix' => '/edocs' ], function() {

        Route::post('/', [
            'name' => 'edocs.upload',
            'uses' => DocumentController::class.'@upload',
        ]);

        Route::delete( '/{uid}',  [
            'name' => 'edocs.delete',
            'uses' => DocumentController::class.'@delete',
        ]);
    });
});
