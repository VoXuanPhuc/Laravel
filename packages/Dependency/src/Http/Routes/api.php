<?php
/**
 * @var Router $router
 */


use Encoda\Dependency\Http\Controllers\DependencyController;
use Encoda\Dependency\Http\Controllers\DependencyScenarioController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {
    Route::group(['prefix' => 'dependency-scenarios'], function(){
        Route::get('/', [
            'as' => 'dependency-scenarios.list',
            'uses' =>  DependencyScenarioController::class. '@index'
        ]);

        Route::post('/', [
            'as' => 'dependency-scenarios.create',
            'uses' =>  DependencyScenarioController::class. '@create'
        ]);


        Route::get('/{uid}', [
            'as' => 'dependency-scenarios.detail',
            'uses' =>  DependencyScenarioController::class. '@detail'
        ]);

        Route::put('/{uid}', [
            'as' => 'dependency-scenarios.update',
            'uses' =>  DependencyScenarioController::class. '@update'
        ]);

        Route::delete('/{uid}', [
            'as' => 'dependency-scenarios.delete',
            'uses' =>  DependencyScenarioController::class. '@delete'
        ]);

        Route::group(['name' => 'dependencies.', 'prefix' => '/{scenarioUID}/dependencies'], static function(){
            Route::get('/', [
                'as' => 'dependencies.list',
                'uses' =>  DependencyController::class. '@index'
            ]);

            Route::post('/', [
                'as' => 'dependencies.create',
                'uses' =>  DependencyController::class. '@create'
            ]);


            Route::get('/{uid}', [
                'as' => 'dependencies.detail',
                'uses' =>  DependencyController::class. '@detail'
            ]);

            Route::put('/{uid}', [
                'as' => 'dependencies.update',
                'uses' =>  DependencyController::class. '@update'
            ]);

            Route::delete('/{uid}', [
                'as' => 'dependencies.delete',
                'uses' =>  DependencyController::class. '@delete'
            ]);
        });
    });
});
