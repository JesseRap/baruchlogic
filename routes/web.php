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


use App\Task;

Route::get('/', 'HomeController@index')->name('home');


Route::get('/home', 'HomeController@index');


Route::get('/about', 'AboutController@index');


Route::get('/videos', 'VideosController@index');

Route::get('/videos/{shortTitle}', 'VideosController@show');

Route::get('/homework', 'HomeworksController@index');

Route::get('/homework/{name}', 'HomeworksController@show');

Route::get('/exercises', 'ExercisesController@index');

Route::get('/exercises/{name}', 'ExercisesController@show')
            ->where('name', '^(?!checkAnswers).+');

Route::post('/exercises/checkAnswers', 'ExercisesController@checkAnswers');


Route::get('/login', 'LoginController@index');

Route::post('/session/login', 'SessionController@login');

Route::get('/session/logout', 'SessionController@logout');

Route::get('/authCheck', function() {echo Auth::check() ? 'TRUE' : 'FALSE';});


Route::get('/admin/login', 'AdminLoginController@index');

Route::post('/session/adminlogin', 'SessionController@adminlogin');

Route::get('/admin', 'AdminController@index')->name('dashboard');
