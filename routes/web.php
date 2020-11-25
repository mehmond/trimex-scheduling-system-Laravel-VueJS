<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminCheck;

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

Route::get('/logout', 'App\Http\Controllers\GenericController@logout');
Route::any('/', 'App\Http\Controllers\GenericController@index');
Route::post('app/register', 'App\Http\Controllers\RegisterController@register');

Route::prefix('app')->middleware([AdminCheck::class])->group(function () {
    ///AUTH
    Route::post('login', 'App\Http\Controllers\LoginController@login');

    ///USERS CONTROLLER
    Route::get('index_users', 'App\Http\Controllers\Admin\UsersController@index');
    Route::get('get_roles', 'App\Http\Controllers\Admin\UsersController@getRoles');
    Route::post('update_user', 'App\Http\Controllers\Admin\UsersController@update');
    Route::post('destroy_user', 'App\Http\Controllers\Admin\UsersController@destroy');

    ///COURSE CONTROLLER
    Route::get('index_course', 'App\Http\Controllers\Admin\CoursesController@index');
    Route::post('store_course', 'App\Http\Controllers\Admin\CoursesController@store');
    Route::post('update_course', 'App\Http\Controllers\Admin\CoursesController@update');
    Route::post('destroy_course', 'App\Http\Controllers\Admin\CoursesController@destroy');

    ///Campus CONTROLLER
    Route::get('index_campus', 'App\Http\Controllers\Admin\CampusesController@index');
    Route::post('store_campus', 'App\Http\Controllers\Admin\CampusesController@store');
    Route::post('update_campus', 'App\Http\Controllers\Admin\CampusesController@update');
    Route::post('destroy_campus', 'App\Http\Controllers\Admin\CampusesController@destroy');

    ///ROOM CONTROLLER
    Route::get('index_room', 'App\Http\Controllers\Admin\RoomsController@index');
    Route::post('store_room', 'App\Http\Controllers\Admin\RoomsController@store');
    Route::post('update_room', 'App\Http\Controllers\Admin\RoomsController@update');
    Route::post('destroy_room', 'App\Http\Controllers\Admin\RoomsController@destroy');

    ///PERMISSION CONTROLLER
    Route::post('assign_roles', 'App\Http\Controllers\Admin\PermissionsController@assignPermission');

    ///SUBJECTS CONTROLLER
    Route::get('index_subject', 'App\Http\Controllers\Admin\SubjectsController@index');
    Route::post('store_subject', 'App\Http\Controllers\Admin\SubjectsController@store');
    Route::post('update_subject', 'App\Http\Controllers\Admin\SubjectsController@update');
    Route::post('destroy_subject', 'App\Http\Controllers\Admin\SubjectsController@destroy');

    ///TIME CONTROLLER
    Route::get('index_time', 'App\Http\Controllers\Admin\TimesController@index');
    Route::post('store_time', 'App\Http\Controllers\Admin\TimesController@store');
    Route::post('update_time', 'App\Http\Controllers\Admin\TimesController@update');
    Route::post('destroy_time', 'App\Http\Controllers\Admin\TimesController@destroy');

    ///SECTION CONTROLLER
    //Route::get('index_year', 'App\Http\Controllers\Admin\SectionsController@indexYear');
    Route::get('index_section', 'App\Http\Controllers\Admin\SectionsController@index');
    Route::post('store_section', 'App\Http\Controllers\Admin\SectionsController@store');
    Route::get('show_section/{id}', 'App\Http\Controllers\Admin\SectionsController@show');
    Route::get('edit_section/{id}', 'App\Http\Controllers\Admin\SectionsController@edit');
    Route::post('update_section/{id}', 'App\Http\Controllers\Admin\SectionsController@update');
    Route::post('destroy_section', 'App\Http\Controllers\Admin\SectionsController@destroy');
});

Route::prefix('app')->group(function () {
    Route::get('count_user', 'App\Http\Controllers\GenericController@countUser');
    Route::get('count_subject', 'App\Http\Controllers\GenericController@countSubject');
    Route::get('index_year', 'App\Http\Controllers\GenericController@indexYear');
    Route::get('index_day', 'App\Http\Controllers\GenericController@indexDay');
    Route::get('index_filtered_subject', 'App\Http\Controllers\GenericController@indexSubject');
    Route::get('index_subject', 'App\Http\Controllers\Admin\SubjectsController@index');
    Route::get('index_section', 'App\Http\Controllers\Admin\SectionsController@index');
    Route::get('show_section/{id}', 'App\Http\Controllers\Admin\SectionsController@show');
    Route::get('index_course', 'App\Http\Controllers\Admin\CoursesController@index');
    Route::get('index_time', 'App\Http\Controllers\Admin\TimesController@index'); 

    ///PREFERRED SUBJECT CONTROLLER
    Route::get('index_preferred_subject', 'App\Http\Controllers\Faculty\PreferredSubjectsController@index');  
    Route::post('store_preferred_subject', 'App\Http\Controllers\Faculty\PreferredSubjectsController@store');
    Route::post('destroy_preferred_subject', 'App\Http\Controllers\Faculty\PreferredSubjectsController@destroy');

     ///PREFERRED TIME CONTROLLER
     Route::get('index_preferred_time', 'App\Http\Controllers\Faculty\PreferredTimesController@index');
     Route::post('store_preferred_time', 'App\Http\Controllers\Faculty\PreferredTimesController@store');
     Route::post('destroy_preferred_time', 'App\Http\Controllers\Faculty\PreferredTimesController@destroy');

});

Route::post('test', 'App\Http\Controllers\GenericController@getSubjects');
Route::any('{path}', 'App\Http\Controllers\GenericController@index')->where('path',  '.*');