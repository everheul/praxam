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
Route::get('/exam', 'ExamController@index')->name('exam.index');
Route::get('/exam/create','ExamController@create')->name('exam.create');
Route::post('/exam/store','ExamController@store')->name('exam.store');
Route::prefix('/exam/{exam_id}')->group(function () {
	Route::get('/','ExamController@show');
	Route::get('/show','ExamController@show')->name('exam.show');
	Route::get('/edit','ExamController@edit')->name('exam.edit');
	Route::post('/update','ExamController@update')->name('exam.update');
	Route::post('/destroy','ExamController@destroy')->name('exam.destroy');
	//- all exam/scene actions
	Route::get('/scene', 'SceneController@index')->name('exam.scene.index');
	Route::get('/scene/create','SceneController@create')->name('exam.scene.create');
	Route::post('/scene/store','SceneController@store')->name('exam.scene.store');
	Route::prefix('/scene/{scene_id}')->group(function () {
		Route::get('/','SceneController@show');
		Route::get('/show','SceneController@show')->name('exam.scene.show');
		Route::get('/edit','SceneController@edit')->name('exam.scene.edit');
		Route::post('/update','SceneController@update')->name('exam.scene.update');
		Route::post('/destroy','SceneController@destroy')->name('exam.scene.destroy');
		Route::post('/order','SceneController@order')->name('exam.scene.order');
		Route::get('/next','SceneController@nextScene')->name('exam.scene.next');
		Route::get('/next/edit','SceneController@editNextScene')->name('exam.scene.next.edit');
		//- all exam/scene/question actions
		Route::prefix('/question')->group(function () {
			Route::get('/', 'QuestionController@index')->name('exam.scene.question.index');
			Route::get('/create', 'QuestionController@create')->name('exam.scene.question.create');
			Route::post('/store', 'QuestionController@store')->name('exam.scene.question.store');
			Route::prefix('/{question_id}')->group(function () {
				Route::get('/','QuestionController@show');
				Route::get('/show','QuestionController@show')->name('exam.scene.question.show');
				Route::get('/edit','QuestionController@edit')->name('exam.scene.question.edit');
				Route::post('/update','QuestionController@update')->name('exam.scene.question.update');
				Route::post('/destroy','QuestionController@destroy')->name('exam.scene.question.destroy');
				Route::get('/answers','QuestionController@answers')->name('exam.scene.question.answers');
				Route::post('/order','QuestionController@order')->name('exam.scene.question.order');
				Route::get('/next','QuestionController@nextQuestion')->name('exam.scene.question.next');
				Route::get('/next/edit','QuestionController@editNextQuestion')->name('exam.scene.question.next.edit');
				//- answers
				Route::prefix('/answer')->group(function () {
					Route::post('/store','AnswerController@store')->name('exam.scene.question.answer.store');
					Route::post('/{answer_id}/destroy','AnswerController@destroy')->name('exam.scene.question.answer.destroy');
				});
			});
		});
	});
});

//- test
Route::get('/crest/question', 'QuestionController@index');

