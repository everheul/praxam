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

//- AJAX path for all events; uses 'action' argument for distinction (obsolete)
// Route::post('/ajax', 'AjaxController@handle');

//- users home page
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/prax', 'HomeController@index');


//- all userexam (prax) actions
//- create userexam:
Route::prefix('/prax/{exam_id}')->group(function () {
	//- note: these two use the exam id, no user-exam yet!
	Route::get('/create','UserExamController@create');
	Route::post('/store','UserExamController@store');
});

Route::prefix('/prax/{prax_id}')->group(function () {
	Route::get('/','UserExamController@show');
	Route::delete('/','UserExamController@destroy');
	Route::get('/show','UserExamController@show');
	Route::get('/destroy','UserExamController@destroy');
	Route::get('/next','UserQuestionController@nextQuestion'); //'UserExamController@nextScene');
	//- all prax/scene actions
	Route::get('/scene', 'UserSceneController@index');
	Route::get('/scene/{s_order}','UserSceneController@show');
//	Route::get('/scene/{s_order}/show','UserSceneController@show');
	Route::get('/scene/{s_order}/next','UserExamController@nextScene');
	//- one prax/scene/question action
	Route::get('/scene/{s_order}/question/{q_order?}','UserSceneController@show');
	Route::get('/scene/{s_order}/question/{q_order}/next','UserQuestionController@nextQuestion');
});

//- all user answers go to:
Route::post('/answer','UserQuestionController@userAnswer');


//--- authorized ADMINS and EXAM OWNERS

/**
 *  edit paths:
 *  exam/{id}/edit											-> edit the exam fields, like head, name, intro..
 *  exam/{id}/scene/{id}/edit								-> edit the scene fields, like head, text, image (depends on type)
 *  exam/{id}/scene/{id}/question/{id}/edit					-> edit the question fields, like head, text, points..
 *  exam/{id}/scene/{id}/question/{id}/answer/{id}/edit		-> edit the answer fields, like text, is_correct, order..
 */

//- all exam actions
Route::get('/exam', 'ExamController@index');
Route::get('/exam/create','ExamController@create');
Route::post('/exam/store','ExamController@store');
Route::prefix('/exam/{exam_id}')->group(function () {
	Route::get('/','ExamController@show');
	Route::get('/show','ExamController@show');
	Route::get('/edit','ExamController@edit');
	Route::post('/update','ExamController@update');
	Route::get('/destroy','ExamController@destroy');
	//- all exam/scene actions
	Route::get('/scene', 'SceneController@index');
	Route::get('/scene/create','SceneController@create');
	Route::post('/scene/store','SceneController@store');
	Route::prefix('/scene/{scene_id}')->group(function () {
		Route::get('/','SceneController@show');
		Route::get('/show','SceneController@show');
		Route::get('/edit','SceneController@edit');
		Route::post('/update','SceneController@update');
		Route::get('/destroy','SceneController@destroy');
		Route::get('/next','SceneController@nextScene');
		//- all exam/scene/question actions
		Route::prefix('/question')->group(function () {
			Route::get('/', 'QuestionController@index');
			Route::get('/create', 'QuestionController@create');
			Route::get('/store', 'QuestionController@store');
			Route::prefix('/question/{question_id}')->group(function () {
				Route::get('/','QuestionController@show');
				Route::get('/show','QuestionController@show');
				Route::get('/edit','QuestionController@edit');
				Route::post('/update','QuestionController@update');
				Route::get('/destroy','QuestionController@destroy');
				Route::get('/next','QuestionController@nextQuestion');
			});
		});
	});
});

//- test
Route::get('/crest/question', 'QuestionController@index');

