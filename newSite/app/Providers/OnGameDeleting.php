<?php

namespace AIBattle\Providers;

use AIBattle\Game;
use AIBattle\Tournament;
use Illuminate\Support\ServiceProvider;

class OnGameDeleting extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Game::deleting(function($game){
            Tournament::where('game_id', $game->id)->delete();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
