<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' => '/auth'], function ($router) {
    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
    Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->name('register');
    Route::get('resetPassword/{email}', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword'])->name('resetPassword');
    Route::post('savePassword', [\App\Http\Controllers\Api\AuthController::class, 'savePasswordReset'])->name('savePasswordReset');
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh'])->name('refresh');
    Route::post('me', [\App\Http\Controllers\Api\AuthController::class, 'me'])->name('me');

    Route::group(['prefix' => '/product'], function($router) {
        Route::post('/', [\App\Http\Controllers\Api\ProductController::class, 'store']);
        Route::get('/list', [\App\Http\Controllers\Api\ProductController::class, 'getList']);
        Route::get('/{id}', [\App\Http\Controllers\Api\ProductController::class, 'index']);
        Route::put('/update/{id}', [\App\Http\Controllers\Api\ProductController::class, 'update']);
        Route::delete('/delete/{id}', [\App\Http\Controllers\Api\ProductController::class, 'delete']);
    });
    Route::group(['prefix' => '/shopping-list'], function($router) {
        Route::post('/', [\App\Http\Controllers\Api\ShoppingListController::class, 'store']);
        Route::get('/list/{userId}', [\App\Http\Controllers\Api\ShoppingListController::class, 'getList']);
        Route::get('/{id}', [\App\Http\Controllers\Api\ShoppingListController::class, 'index']);
        Route::put('/{id}', [\App\Http\Controllers\Api\ShoppingListController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\ShoppingListController::class, 'delete']);
    });
});
