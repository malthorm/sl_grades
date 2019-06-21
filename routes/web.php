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
Route::get('/Shibboleth.sso/Login?target=https://www.tu-chemnitz.de/~malth/', function () {
    return view('login');
})->name('login');

Route::get('/', function () {
    //if (student)
    return view('student');
    //if (mitarbeiter)

    // else
    //     return view('login');
});

Route::get('loginTest', 'CourseController@testAuth'); //test

Route::get('courses/search', 'CourseController@search');
Route::resource('courses', 'CourseController');
Route::get('grades', 'GradingController@index');
//my-grades just {student_id}
Route::get('grades/my-grades', 'GradingController@show');
Route::post('grades/{course}', 'GradingController@store');
Route::post('grades/{course}/csv', 'GradingController@csvImport');
Route::delete('grades/{grading}', 'GradingController@destroy');
