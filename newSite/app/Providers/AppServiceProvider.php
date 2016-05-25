<?php

namespace AIBattle\Providers;

use AIBattle\Tournament;
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
                ->with('globalCurrentTournaments', Tournament::where('state', 'running')->get(['id', 'name']));
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
