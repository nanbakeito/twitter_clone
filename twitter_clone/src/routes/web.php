<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// ログイン状態
Route::group(['middleware' => 'auth'], function() {

    // ユーザ関連のリソース
    Route::resource('users', 'App\Http\Controllers\UsersController', ['only' => ['index', 'show', 'edit', 'update']]);
    // リソースにはない，フォロー/フォロー解除を追加
    Route::post('users/follow/{id}', 'App\Http\Controllers\UsersController@follow')->name('follow');
    Route::delete('users/unFollow', 'App\Http\Controllers\UsersController@unFollow')->name('unFollow');
    // コメント
    Route::resource('comments', 'App\Http\Controllers\CommentsController', ['only' => ['store']]);
    // いいね
    Route::post('tweets/favorite/{id}', 'App\Http\Controllers\FavoritesController@favorite')->name('tweets.favorite');

    // 以下API
    // フォロー機能
    Route::get('api/follow', 'App\Http\Controllers\API\FollowController@follow');
    // ユーザー関連
    Route::get('api/fetchUserTimeLines', 'App\Http\Controllers\API\UserController@fetchUserTimeLines');
    Route::get('api/sortUserTimeLines', 'App\Http\Controllers\API\UserController@sortUserTimeLines');
    // コメントcrud機能
    Route::post('api/postComment', 'App\Http\Controllers\API\CommentController@postComment')->middleware('validationComment');
    Route::get('api/getComment', 'App\Http\Controllers\API\CommentController@getComment');
    Route::delete('api/deleteComment/{id}', 'App\Http\Controllers\API\CommentController@deleteComment');
    // ツイート関連
    Route::get('api/fetchTimeLine', 'App\Http\Controllers\API\TweetController@fetchTimeLine');
    Route::delete('api/deleteTweet/{id}', 'App\Http\Controllers\API\TweetController@deleteTweet');
    Route::post('api/postTweet', 'App\Http\Controllers\API\TweetController@postTweet')->middleware('validationTweet');
    Route::get('api/sortTimeLine', 'App\Http\Controllers\API\TweetController@sortTimeLine');
    Route::get('api/favorite', 'App\Http\Controllers\API\TweetController@favorite');
}); 

// ツイート関連
Route::resource('tweets', 'App\Http\Controllers\TweetsController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);
