<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ProductController;

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

Route::resource('product', ProductController::class, ['only' => [
        'index', 'store', 'show', 'update', 'destroy'
    ]]);

Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPasswordEmail'])->middleware(['guest'])->name('password.email');


Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware(['guest'])->name('password.reset');
