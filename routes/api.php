<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);


});

Route::group([
    'middleware' => 'auth:api',

], function ($router) {
    Route::resource('product', ProductController::class, ['only' => [
        'index', 'store', 'show', 'update', 'destroy'
    ]]);
    Route::resource('user', UserController::class, ['only' => [
        'index', 'store', 'show', 'update', 'destroy'
    ]]);
});

Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPasswordEmail'])->middleware(['guest'])->name('password.email');

Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware(['guest'])->name('password.reset');


