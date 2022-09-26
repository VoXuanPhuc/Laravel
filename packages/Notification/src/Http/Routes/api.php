<?php
/**
 * @var Router $router
 */


use Encoda\Organization\Http\Controllers\Tenant\BusinessUnitController;
use Encoda\Organization\Http\Controllers\Tenant\DivisionController;
use Illuminate\Support\Facades\Route;
use Laravel\Lumen\Routing\Router;

Route::group( ['prefix' => '/identity/api/v1/', 'middleware' => 'auth'] , function() {

});
