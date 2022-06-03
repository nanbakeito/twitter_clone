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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// 検索機能　**未実装
Route::get('/following', 'App\Http\Controllers\API\UserController@getFollowing');
Route::get('/follower', 'App\Http\Controllers\API\UserController@getFollower');
// コメントcrud機能
Route::post('/postComment', 'App\Http\Controllers\API\CommentController@postComment')->middleware('validationComment');
Route::get('/getComment', 'App\Http\Controllers\API\CommentController@getComment');
Route::delete('/deleteComment/{id}', 'App\Http\Controllers\API\CommentController@deleteComment');
