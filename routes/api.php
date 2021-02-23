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

Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/user', 'CommonController@getUser')->name('get.user');
Route::get('/profile', 'CommonController@getProfile')->name('get.profile');

Route::get('/groups/{group}', 'GroupController@getGroup')->name('get.group');

Route::group(['middleware' => 'auth'],function() {
  //プロフィール作成ページ
  Route::post('/profile/create', 'ProfileController@create')->name('create.profile');
  //グループ一覧
  Route::get('/mypage/{user}/groups','GroupController@index')->name('index.group');
  //グループを作成する
  Route::post('/mypage/{user}/groups/create', 'GroupController@create')->name('create.group');
});
