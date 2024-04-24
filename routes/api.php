<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth', [LoginController::class, 'checkAuth']);
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('me', [UserController::class, 'me']);
    Route::put('me', [UserController::class, 'updateMe']);
    Route::delete('me', [UserController::class, 'deleteMe']);
});

Route::post('/tweets', [TweetController::class, 'store'])->middleware('auth:sanctum');
Route::get('/tweets', [TweetController::class, 'index']);
Route::post('/tweets/{id}/like', [TweetController::class, 'like'])->middleware('auth:sanctum');

Route::prefix('users')->group(function () {
    Route::get('{id}', [UserController::class, 'show']);
    Route::get('{id}/tweets', [UserController::class, 'tweets']);
});

/* Route::get('/tweets', function () {
    return response()->json(['response' => 'Biip Biip Biip']);
}); */