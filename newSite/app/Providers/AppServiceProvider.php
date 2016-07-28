<?php

namespace AIBattle\Providers;

use AIBattle\Tournament;
use AIBattle\Game;
use Illuminate\Support\Facades\Auth;
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
        View::composer('layouts.mainLayout', function($view) {
            $view
                ->with('globalCurrentUser', Auth::user())
                ->with('globalCurrentTournaments', Tournament::where('state', 'running')->get(['id', 'name']))
                ->with('globalGamesAvailable', Game::all(['id', 'name']))
                ->with('globalClosedTournaments', Tournament::where('state', 'closed')->get(['id', 'name']))
                ->with('globalPreparingTournaments', Tournament::where('state', 'preparing')->get(['id', 'name']));
        });
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
