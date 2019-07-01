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

Route::get('/', 'Controller@defaultView');

Route::get('courses/search', 'CourseController@search');
Route::resource('courses', 'CourseController');

Route::get('grades', 'GradingController@index');
Route::post('grades/{course}', 'GradingController@store');
Route::post('grades/{course}/csv', 'GradingController@csvImport');
Route::delete('grades/{grading}', 'GradingController@destroy');
