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
    return view('welcome');
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
    Route::post('users/follow', 'App\Http\Controllers\UsersController@follow')->name('follow');
    Route::delete('users/unFollow', 'App\Http\Controllers\UsersController@unFollow')->name('unFollow');
}); 
