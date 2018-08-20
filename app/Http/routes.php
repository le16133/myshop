<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


































//------------------------------litao---------------------------------
// 后来轮播管理路由
Route::resource('/admin/lunbo','Admin\LunboController');
// 后台友情链接管理
Route::resource('admin/link','Admin\LinkController');
// 后台分类路由
Route::resource('/admin/cate','Admin\CateController');
//分类管理隐式路由
Route::controller('/admin/cate','Admin\CateController');

// 前台推荐模块
Route::resource('/admin/recommend','Admin\RecommendController');
// 后台主页
Route::resource('/home/index','Home\IndexController');














































//------------------------------litao---------------------------------