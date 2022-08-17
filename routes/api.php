<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

//Route::resource('products', ProductController::class);

Route::middleware(['auth:api'])->group(function () {
    Route::get('get-user', [PassportAuthController::class, 'userInfo']);
    Route::get('logout', [PassportAuthController::class, 'logout']);

    Route::resource('products', ProductController::class);
    Route::put('assign-product/{product}', [ProductController::class, 'assignProductToUser']);
    Route::put('update-status-product/{product}', [ProductController::class, 'updateStatusProduct']);

    Route::get('products-user', [ProductController::class, 'productsOfUser']);

});
