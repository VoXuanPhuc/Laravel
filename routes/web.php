<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group( ['prefix' => '/identity'], function () use ($router) {

    $router->get('/', function () use ($router) {
        return \response()->json('Identity Services');
    });

    $router->get('/health-check', function () use ($router) {
        return \response()->json('Founds');
    });

    $router->get('hello',[
        'as' => 'hello', 'uses' => 'ExampleController@hello'
    ]);

});
