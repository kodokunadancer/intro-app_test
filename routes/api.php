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
  //グループを検索する
  Route::post('/mypage/{user}/groups/reserch', 'GroupController@reserch')->name('reserch.group');
  //グループ参加
  Route::get('/mypage/{user}/groups/{group}/join','GroupController@join')->name('join.group');

  Route::group(['middleware' => 'can:view,user'], function() {
    //マイプロフィールの編集
    Route::post('/mypage/{user}/myprofile/edit', 'ProfileController@editMyProfile')->name('edit.myProfile');
  });

  //ユーザーとグループの紐づきの有無を確認
  Route::group(['middleware' => 'can:view,group'],function() {
    //グループ退会
    Route::get('/mypage/{user}/groups/{group}/exit','GroupController@exit')->name('exit.group');
    //グループ編集
    Route::post('/mypage/{user}/groups/{group}/edit','GroupController@edit')->name('edit.group');
    //コメント投稿処理
    Route::post('/mypage/{user}/groups/{group}/profiles/{profile}/comments', 'ProfileController@addComment')->name('add.comment');
  });

});

Route::get('/refresh-token', 'CommonController@refreshToken')->name('refresh.token');
