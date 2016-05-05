<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'StartController@start');

// Authentication routes
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@logout');

// Registration routes
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');


// Admin panel routes
Route::group(['prefix' => 'adminPanel', 'middleware' => 'auth.admin'], function() {
    Route::get('/', 'AdminPanel\MainController@showView');

    Route::group(['prefix' => 'news'], function() {
        Route::get('/', 'AdminPanel\NewsController@showNews');
        Route::get('/create', 'AdminPanel\NewsController@showCreateNewsForm');
        Route::post('/create', 'AdminPanel\NewsController@createNews');
        Route::get('/edit/{id}', 'AdminPanel\NewsController@getEditNews');
        Route::post('/edit/{id}', 'AdminPanel\NewsController@editNews');

    });

    Route::get('/users', 'AdminPanel\UsersController@showUsers');
});

// userProfile
/*
Route::get('/userProfile', 'UserProfileController@showAuthUserView'); // for current user
Route::get('/userProfile/{id}', 'UserProfileController@showUserView'); // for admin
*/

Route::group(['prefix' => 'userProfile'], function() {
    // for current user
    Route::get('/', 'UserProfileController@showAuthUserView');
    Route::get('/update', 'UserProfileController@showUpdateProfileView');
    Route::post('/update', 'UserProfileController@updateUserProfileSelf');

    // for admin
    Route::get('/{id}', 'UserProfileController@showUserView');
    Route::get('/update/{id}', 'UserProfileController@showUpdateProfileToAdminView');
    Route::post('/update/{id}', 'UserProfileController@updateUserProfileAdmin');
});