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


Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Auth::routes();

Route::group(['namespace' => 'Admin', 'prefix'=>'/admin'], function() {    
    Route::get('/', 'OptionController@index');
    
    Route::post('/options/listing', 'OptionController@listing');
    Route::resource('/options', 'OptionController');
    
    Route::get('/roles/', 'RoleController@index');
    Route::post('/roles/listing', 'RoleController@listing');
    Route::resource('/roles', 'RoleController');
    
    Route::get('/permissions/', 'PermissionController@index');
    Route::post('/permissions/listing', 'PermissionController@listing');
    Route::resource('/permissions', 'PermissionController'); 
    
    Route::post('/users/listing', 'UserController@listing');
    Route::resource('/users', 'UserController'); 
    
    Route::post('/groups/listing', 'GroupController@listing');
    Route::resource('/groups', 'GroupController'); 
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', 'HomeController@index');;

Route::group(['namespace' => 'Site', 'prefix' => '/site'], function() {    
    Route::post('/change_language', 'SiteController@change_language');
});

