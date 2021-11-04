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
Route::group([
    'middleware' => 'api',
    'prefix' => '/auth'

], function ($router) {
    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
    Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->name('register');
    Route::get('resetPassword/{email}', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword'])->name('resetPassword');
    Route::post('savePassword', [\App\Http\Controllers\Api\AuthController::class, 'savePasswordReset'])->name('savePasswordReset');
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh'])->name('refresh');
    Route::post('me', [\App\Http\Controllers\Api\AuthController::class, 'me'])->name('me');
    Route::group(['prefix' => '/mark'], function($router) {
        Route::post('create', [\App\Http\Controllers\Api\MarkController::class, 'newMark']);
        Route::get('list/{userId}', [\App\Http\Controllers\Api\MarkController::class, 'getListMark']);
        Route::get('get/{id}', [\App\Http\Controllers\Api\MarkController::class, 'getMark']);
        Route::put('edit/{id}', [\App\Http\Controllers\Api\MarkController::class, 'editMark']);
        Route::delete('delete/{id}', [\App\Http\Controllers\Api\MarkController::class, 'deleteMark']);
    });
});
