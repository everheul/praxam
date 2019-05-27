<?php

use Illuminate\Http\Request;

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

//- public welcome page
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//--- authorized USERS only

//- AJAX path for all events; uses 'action' argument for distinction
Route::post('/ajax', 'AjaxController@handle');

//- users home page
Route::get('/home', 'HomeController@index')->name('home');

//- all userexam (prax) actions
Route::get('/prax', 'UserExamController@index');
Route::prefix('/prax/{exam_id?}')->group(function () {
	//- note: these two use the exam id!
	Route::get('/create','UserExamController@create');
	Route::post('/store','UserExamController@store');
});
Route::prefix('/prax/{prax_id?}')->group(function () {
	Route::get('/show','UserExamController@show');
	Route::get('/kill','UserExamController@kill');
	//- all prax/scene actions
	Route::get('/scene', 'UserSceneController@index');
	Route::get('/scene/{order?}','UserSceneController@show');
	Route::get('/scene/{order?}/show','UserSceneController@show');
});

//--- authorized ADMINS only

//- all exam actions
Route::get('/exam', 'ExamController@index');
Route::prefix('/exam/{exam_id?}')->group(function () {
	Route::get('/show','ExamController@show');
	Route::get('/create','ExamController@create');
	Route::post('/store','ExamController@store');
	Route::get('/edit','ExamController@edit');
	Route::post('/update','ExamController@update');
	Route::get('/kill','ExamController@kill');
	//- all exam/scene actions
	Route::get('/scene', 'SceneController@index');
	Route::prefix('/scene/{scene_id?}')->group(function () {
		Route::get('/show','SceneController@show');
		Route::get('/create','SceneController@create');
		Route::post('/store','SceneController@store');
		Route::get('/edit','SceneController@edit');
		Route::post('/update','SceneController@update');
		Route::get('/kill','SceneController@kill');
		//- any exam/scene/question actions?
	});
});

/*
Route::get('/exam/{examid?}', 'ExamController@show');
Route::get('/exam/edit/{examid?}', 'ExamController@edit');
Route::post('exam/update/{examid?}', 'ExamController@update');
Route::post('exam/store', 'ExamController@store');

Route::get('/test/{testid?}', 'TestController@config');
Route::get('/newtest/{testid?}', 'TestController@newTest');

Route::get('/scene/{id?}', 'SceneController@show');
Route::get('/scene/show/{id?}', 'SceneController@show');
Route::get('/scene/edit/{id?}', 'SceneController@edit');
Route::post('/scene/update/{id?}', 'SceneController@update');

Route::get('/next', function (Request $request) {
    dd($request);
})->name('next');
*/