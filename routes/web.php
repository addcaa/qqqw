<?php

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
Route::get('eadd','Exam\ExamController@eadd');
Route::post('eaddo','Exam\ExamController@eaddo');
//weather
Route::get('weather','Exam\ExamController@weather');
Route::post('weathero','Exam\ExamController@weathero');
Route::post('weathere','Exam\ExamController@weathere');

Route::post('aa','Exam\ExamController@aa');




























//后台
Route::get('adminuser','Admin\AdminController@adminuser');

Route::get('timeuser','Admin\AdminController@timeuser');
Route::get('/', function () {
    return view('welcome');
});
Route::get('adad','Show\ShowController@add');
Route::POST('login','Show\ShowController@login');
Route::get('goods','Show\ShowController@goods');
Route::get('goodss','Show\ShowController@goodss');
Route::get('addres','Show\ShowController@addres');


//a卷
Route::get('add','Login\Login@add');
Route::post('logadd','Login\Login@logadd');
Route::post('loginj','Login\Login@loginj')->middleware('token');
Route::get('index','Login\Login@index');
Route::get('tui','Login\Login@tui');


//07b卷
Route::get('b07','B07b\B07bController@b07');

Route::post('b07in','B07b\B07bController@b07in');
Route::post('b07j','B07b\B07bController@b07j');