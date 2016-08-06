<?php

namespace AIBattle\Providers;

use AIBattle\Round;
use AIBattle\Tournament;
use AIBattle\Game;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        if (Schema::hasTable('tournaments')) {
            $globalCurrentTournaments = Tournament::where('state', 'running')->get(['id', 'name']);

            View::composer('layouts.mainLayout', function($view) use(&$globalCurrentTournaments)  {
                $view
                    ->with('globalCurrentUser', Auth::user())
                    ->with('globalCurrentTournaments', $globalCurrentTournaments);
            });

            View::composer('layouts.adminPanelLayout', function($view) use(&$globalCurrentTournaments) {
                $view
                    ->with('globalCurrentTournaments', $globalCurrentTournaments)
                    ->with('globalGamesAvailable', Game::all(['id', 'name']))
                    ->with('globalClosedTournaments', Tournament::where('state', 'closed')->get(['id', 'name']))
                    ->with('globalPreparingTournaments', Tournament::where('state', 'preparing')->get(['id', 'name']));
            });

            View::composer('assets.adminPanel.tournamentsSidebar', function($view) use(&$globalCurrentTournaments) {
                $view
                    ->with('globalCurrentTournaments', $globalCurrentTournaments)
                    ->with('globalClosedTournaments', Tournament::where('state', 'closed')->get(['id', 'name']))
                    ->with('globalPreparingTournaments', Tournament::where('state', 'preparing')->get(['id', 'name']));
            });

            View::composer('adminPanel.games.*', function($view) {
                $view->with('globalGamesAvailable', Game::all(['id', 'name']));
            });

            View::composer('layouts.tournamentLayout', function($view) use(&$globalCurrentTournaments) {
                $view
                    ->with('globalCurrentTournaments', $globalCurrentTournaments)
                    ->with('globalVisibleRounds', Round::where('visible', 1));
            });
        }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
