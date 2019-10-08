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
Route::get('/home', 'HomeController@index')->name('home'); //in progress
Route::get('/prax', 'HomeController@index');


//- all userexam (prax) actions
//- create userexam:
Route::prefix('/prax/{exam_id?}')->group(function () {
	//- note: these two use the exam id, no user-exam yet!
	Route::get('/create','UserExamController@create');
	Route::post('/store','UserExamController@store');
});
//- show,
Route::prefix('/prax/{prax_id?}')->group(function () {
	Route::get('/','UserExamController@show');
	Route::delete('/','UserExamController@destroy');
	Route::get('/show','UserExamController@show');
	Route::get('/destroy','UserExamController@destroy');
	Route::get('/next','UserExamController@nextScene');
	//- all prax/scene actions
	Route::get('/scene', 'UserSceneController@index');
	Route::get('/scene/{order}','UserSceneController@show');
	Route::get('/scene/{order}/show','UserSceneController@show');
	Route::get('/scene/{order}/next','UserExamController@nextScene');
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
Route::get('/exam/{exam_id?}', 'ExamController@show');
Route::prefix('/exam/{exam_id?}')->group(function () {
	Route::get('/show','ExamController@show');
	Route::get('/create','ExamController@create');
	Route::post('/store','ExamController@store');
	Route::get('/edit','ExamController@edit');
	Route::post('/update','ExamController@update');
	Route::get('/kill','ExamController@kill');
	//- all exam/scene actions
	Route::get('/scene', 'SceneController@index');
        Route::get('/scene/create','SceneController@create');
        Route::post('/scene/store','SceneController@store');
	Route::prefix('/scene/{scene_id?}')->group(function () {
            Route::get('/','SceneController@show');
            Route::get('/show','SceneController@show');
            Route::get('/edit','SceneController@edit');
            Route::post('/update','SceneController@update');
            Route::get('/destroy','SceneController@destroy');
            Route::get('/next','SceneController@nextScene');
            //- any exam/scene/question actions?
	});
});

//- test
Route::get('/crest/question', 'QuestionController@index');

