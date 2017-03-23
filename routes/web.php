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

Auth::routes();

Route::group(['namespace' => 'Admin\RBAC', 'prefix'=>'/admin/rbac'], function() {       
    Route::post('/roles/listing', 'RoleController@listing');
    Route::resource('/roles', 'RoleController');
    
    Route::post('/permissions/listing', 'PermissionController@listing');
    Route::resource('/permissions', 'PermissionController'); 
    
    Route::post('/users/listing', 'UserController@listing');
    Route::resource('/users', 'UserController'); 
    
    Route::post('/groups/listing', 'GroupController@listing');
    Route::resource('/groups', 'GroupController'); 
});

Route::group(['namespace' => 'Management', 'prefix'=>'/management'], function() {       
    Route::post('/movies/listing', 'MovieController@listing');
    Route::resource('/movies', 'MovieController');
    
    Route::post('/genres/listing', 'GenreController@listing');
    Route::resource('/genres', 'GenreController');
    
    Route::post('/attributes/listing', 'AttributeController@listing');
    Route::resource('/attributes', 'AttributeController');
    
    Route::post('/attribute_val/listing', 'AttributeValueController@listing');
    Route::resource('/attribute_val', 'AttributeValueController');
});

Route::get('/', 'HomeController@index');;

Route::group(['namespace' => 'Site', 'prefix' => '/site'], function() {    
    Route::post('/change_language', 'SiteController@change_language');
    Route::get('/profile', 'ProfileController@index');
    Route::post('/profile', 'ProfileController@update');
});

