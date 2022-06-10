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

// 以下API
    // フォロー機能
    Route::get('/follow', 'App\Http\Controllers\Api\FollowController@follow');
    // ユーザー関連
    Route::get('/fetchUserTimeLines', 'App\Http\Controllers\Api\UserController@fetchUserTimeLines');
    Route::get('/sortUserTimeLines', 'App\Http\Controllers\Api\UserController@sortUserTimeLines');
    // コメントcrud機能
    Route::post('/postComment', 'App\Http\Controllers\Api\CommentController@postComment')->middleware('validationComment');
    Route::get('/getComment', 'App\Http\Controllers\Api\CommentController@getComment');
    Route::delete('deleteComment/{id}', 'App\Http\Controllers\Api\CommentController@deleteComment');
    // ツイート関連
    Route::get('/fetchTimeLine', 'App\Http\Controllers\Api\TweetController@fetchTimeLine');
    Route::delete('/deleteTweet/{id}', 'App\Http\Controllers\Api\TweetController@deleteTweet');
    Route::post('/postTweet', 'App\Http\Controllers\Api\TweetController@postTweet');
    Route::get('/sortTimeLine', 'App\Http\Controllers\Api\TweetController@sortTimeLine');
    Route::get('/favorite', 'App\Http\Controllers\Api\TweetController@favorite');


