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
// フォロー機能
Route::get('/follow', 'App\Http\Controllers\API\FollowController@follow');
// 検索　**未実装
Route::get('/fetchUserTimeLines', 'App\Http\Controllers\API\UserController@fetchUserTimeLines');
Route::get('/narrowDownUserTimeLines', 'App\Http\Controllers\API\UserController@narrowDownUserTimeLines');

Route::get('/fetchFollow', 'App\Http\Controllers\API\UserController@fetchFollow');
Route::get('/fetchFollower', 'App\Http\Controllers\API\UserController@fetchFollower');
// コメントcrud機能
Route::post('/postComment', 'App\Http\Controllers\API\CommentController@postComment')->middleware('validationComment');
Route::get('/getComment', 'App\Http\Controllers\API\CommentController@getComment');
Route::delete('/deleteComment/{id}', 'App\Http\Controllers\API\CommentController@deleteComment');
