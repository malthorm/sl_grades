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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/courses', 'CoursesController@index');
Route::get('/courses/create', 'CoursesController@create');
Route::delete('/courses/{course}', 'CoursesController@destroy');
Route::get('/courses/{course}', 'CoursesController@show');
Route::post('/courses', 'CoursesController@store');
