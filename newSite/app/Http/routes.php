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

        Route::get('/edit/{id}', 'AdminPanel\NewsController@showEditNewsForm');
        Route::post('/edit/{id}', 'AdminPanel\NewsController@editNews');

        Route::get('/{id}', 'AdminPanel\NewsController@showNewsById'); // last, because if number passed - we show news by that id

    });

    Route::group(['prefix' => 'games'], function () {
        Route::get('/', 'AdminPanel\GamesController@showGames');

        Route::get('/create', 'AdminPanel\GamesController@showCreateGameForm');
        Route::post('/create', 'AdminPanel\GamesController@createGame');

        Route::get('/edit/{id}', 'AdminPanel\GamesController@showEditGameForm');
        Route::post('/edit/{id}', 'AdminPanel\GamesController@editGame');

        Route::get('/{id}', 'AdminPanel\GamesController@showGameById'); // last, because if number passed - we show news by that id
    });

    Route::group(['prefix' => 'checkers'], function () {
        Route::get('/', 'AdminPanel\CheckersController@showCheckers');

        Route::get('/create', 'AdminPanel\CheckersController@showCreateCheckerForm');
        Route::post('/create', 'AdminPanel\CheckersController@createChecker');

        Route::get('/edit/{id}', 'AdminPanel\CheckersController@showEditCheckerForm');
        Route::post('/edit/{id}', 'AdminPanel\CheckersController@editChecker');


        Route::get('/{id}', 'AdminPanel\CheckersController@showCheckerById'); // last, because if number passed - we show news by that id
    });

    Route::get('/users', 'AdminPanel\UsersController@showUsers');
});

Route::group(['prefix' => 'userProfile'], function() {
    // for current user
    Route::get('/', 'UserProfileController@showAuthUserView');
    Route::get('/update', 'UserProfileController@showUpdateProfileView');
    Route::post('/update', 'UserProfileController@updateUserProfileSelf');

    // for admin
    // 'middleware' => 'auth.admin' ???
    Route::get('/{id}', 'UserProfileController@showUserView');
    Route::get('/update/{id}', 'UserProfileController@showUpdateProfileToAdminView');
    Route::post('/update/{id}', 'UserProfileController@updateUserProfileAdmin');
});