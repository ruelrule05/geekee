<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FollowedUserController;
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
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('register', [UserController::class, 'store'])->name('register');

Route::post('follow-user', [FollowedUserController::class, 'store']);
Route::post('unfollow-user', [FollowedUserController::class, 'unfollow']);

Route::get('followed-user/suggested', [FollowedUserController::class, 'suggested']);
Route::get('followed-user/{followedUser}', [FollowedUserController::class, 'show']);
Route::get('followed-user/{followedUser}/tweets', [FollowedUserController::class, 'tweets']);

Route::resource('tweets', TweetController::class)->except(['index', 'create', 'edit']);