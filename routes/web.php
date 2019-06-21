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
use \App\ShibbAuth;

Route::get('/', function () {
    if (!ShibbAuth::isAuthenticated()) {
        return view('index.php/login');
    }
    if (ShibbAuth::authorize('mitarbeiter')) {
        return redirect('index.php/courses');
    }
    if (ShibbAuth::authorize('student')) {
        return redirect('index.php/grades');
    }
});


Route::get('courses/search', 'CourseController@search');
Route::resource('courses', 'CourseController');
Route::get('grades', 'GradingController@index');
// Route::get('grades/my-grades', 'GradingController@show');
Route::post('grades/{course}', 'GradingController@store');
Route::post('grades/{course}/csv', 'GradingController@csvImport');
Route::delete('grades/{grading}', 'GradingController@destroy');
