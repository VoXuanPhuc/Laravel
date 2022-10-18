<?php

use Encoda\Supplier\Http\Controllers\SupplierCategoryController;
use Encoda\Supplier\Http\Controllers\SupplierCertController;
use Encoda\Supplier\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {
    /** =========== Supplier routing ====== */
    Route::group(['name' => 'suppliers.', 'prefix' => 'suppliers'], function(){
        Route::get('/', [
            'as' => 'list',
            'uses' =>  SupplierController::class. '@index'
        ]);

        Route::post('/', [
            'as' => 'create',
            'uses' =>  SupplierController::class. '@create'
        ]);


        Route::get('/{uid}', [
            'as' => 'detail',
            'uses' =>  SupplierController::class. '@detail'
        ]);

        Route::put('/{uid}', [
            'as' => 'update',
            'uses' =>  SupplierController::class. '@update'
        ]);

        Route::delete('/{uid}', [
            'as' => 'delete',
            'uses' =>  SupplierController::class. '@delete'
        ]);

        Route::get('/download/all', [
            'as' => 'download',
            'uses' =>  SupplierController::class. '@download'
        ]);

        Route::group(['name' => 'certs.', 'prefix' => '/{uid}/certs'], function(){
            Route::get('/', [
                'as' => 'index',
                'uses' =>  SupplierCertController::class. '@getCertBySupplier'
            ]);

            Route::delete('/{certUID}', [
                'as' => 'delete',
                'uses' =>  SupplierCertController::class. '@deleteCert'
            ]);
        });
    });


    /** =========== Supplier category routing ====== */

    Route::get('/supplier-categories', [
        'as' => 'suppliers.list',
        'uses' =>  SupplierCategoryController::class. '@index'
    ]);

    Route::post('/supplier-categories', [
        'as' => 'suppliers.create',
        'uses' =>  SupplierCategoryController::class. '@create'
    ]);

    Route::get('/supplier-categories/{uid}', [
        'as' => 'suppliers.detail',
        'uses' =>  SupplierCategoryController::class. '@detail'
    ]);

    Route::put('/supplier-categories/{uid}', [
        'as' => 'suppliers.update',
        'uses' =>  SupplierCategoryController::class. '@update'
    ]);

    Route::delete('/supplier-categories/{uid}', [
        'as' => 'suppliers.delete',
        'uses' =>  SupplierCategoryController::class. '@delete'
    ]);
});
