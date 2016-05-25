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


Route::group(['middleware' => 'locale'], function() {
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

            Route::get('/{id}', 'AdminPanel\NewsController@showNewsById');

        });

        Route::group(['prefix' => 'games'], function () {
            Route::get('/', 'AdminPanel\GamesController@showGames');

            Route::get('/create', 'AdminPanel\GamesController@showCreateGameForm');
            Route::post('/create', 'AdminPanel\GamesController@createGame');

            Route::get('/edit/{id}', 'AdminPanel\GamesController@showEditGameForm');
            Route::post('/edit/{id}', 'AdminPanel\GamesController@editGame');

            Route::get('/{id}', 'AdminPanel\GamesController@showGameById');

            Route::group(['prefix' => '/{id}/attachments'], function() {
                Route::get('/', 'AdminPanel\AttachmentsController@showAttachments');

                Route::get('/create', 'AdminPanel\AttachmentsController@showCreateAttachmentForm');
                Route::post('/create', 'AdminPanel\AttachmentsController@createAttachment');

                Route::get('/edit/{attachmentId}', 'AdminPanel\AttachmentsController@showEditAttachmentForm');
                Route::post('/edit/{attachmentId}', 'AdminPanel\AttachmentsController@editAttachment');

                Route::get('/{attachmentId}', 'AdminPanel\AttachmentsController@showAttachmentById');
            });
        });

        Route::group(['prefix' => 'checkers'], function () {
            Route::get('/', 'AdminPanel\CheckersController@showCheckers');

            Route::get('/create', 'AdminPanel\CheckersController@showCreateCheckerForm');
            Route::post('/create', 'AdminPanel\CheckersController@createChecker');

            Route::get('/edit/{id}', 'AdminPanel\CheckersController@showEditCheckerForm');
            Route::post('/edit/{id}', 'AdminPanel\CheckersController@editChecker');

            Route::get('/{id}', 'AdminPanel\CheckersController@showCheckerById');
        });

        Route::group(['prefix' => 'tournaments'], function() {
            Route::get('/', 'AdminPanel\TournamentsController@showTournaments');

            Route::get('/create', 'AdminPanel\TournamentsController@showCreateTournamentForm');
            Route::post('/create', 'AdminPanel\TournamentsController@createTournament');

            Route::get('/show/{state}', 'AdminPanel\TournamentsController@showExtendedTournamentTable');

            Route::get('/edit/{id}', 'AdminPanel\TournamentsController@showEditTournamentForm');
            Route::post('/edit/{id}', 'AdminPanel\TournamentsController@editTournament');

            // ajax
            Route::get('/ajax/getCheckersByGameId/{id}', ['uses' =>'AdminPanel\TournamentsController@getCheckersByGameId']);

            Route::get('/{id}', 'AdminPanel\TournamentsController@showTournamentById');
            Route::get('/{id}/strategies', 'AdminPanel\TournamentsController@showUsersStrategies');

            Route::get('/{id}/strategies/edit/{strategyId}', 'Tournament\StrategiesController@showEditStrategyFormAdminPanel');
            Route::post('/{id}/strategies/edit/{strategyId}', 'Tournament\StrategiesController@editStrategyAdminPanel');

            Route::get('/{id}/strategies/{strategyId}', 'Tournament\StrategiesController@showStrategyProfileAdminPanel');
            Route::get('/{id}/strategies/{strategyId}/setActive', 'Tournament\StrategiesController@setStrategyActiveAdminPanel');


        });

        Route::get('/users', 'AdminPanel\UsersController@showUsers');
    });

    Route::group(['prefix' => 'tournaments/{id}', 'middleware' => 'tournamentAccess'], function() {
        Route::get('/', 'Tournament\MainController@showTournament');

        Route::group(['prefix' => 'strategies', 'middleware' => 'auth'], function() {
            Route::get('/', 'Tournament\StrategiesController@showStrategies');

            Route::get('/create', 'Tournament\StrategiesController@showCreateStrategyForm');
            Route::post('/create', 'Tournament\StrategiesController@createStrategy');

            Route::get('/edit/{strategyId}', 'Tournament\StrategiesController@showEditStrategyFormPublic');
            Route::post('/edit/{strategyId}', 'Tournament\StrategiesController@editStrategyPublic');

            Route::get('/{strategyId}', 'Tournament\StrategiesController@showStrategyProfilePublic');
            Route::get('/{strategyId}/setActive', 'Tournament\StrategiesController@setStrategyActivePublic');
        });
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

    Route::group(['prefix' => 'download'], function() {
        Route::get('/game/{id}/attachment/{attachmentId}', 'DownloadController@downloadAttachment')->middleware('auth.admin');
        Route::get('/tournament/{id}/attachment/{attachmentId}', 'DownloadController@downloadAttachmentByTournament');

        Route::get('/game/{id}/archive', 'DownloadController@downloadGameArchive')->middleware('auth.admin');
    });

    Route::get('locale/{locale}', 'StartController@switchLanguage');
});
