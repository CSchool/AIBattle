<?php

namespace AIBattle\Http\Controllers\Tournament;

use AIBattle\Tournament;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;

class MainController extends Controller
{

    public function showTournament($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);
        return view('tournaments/main', ['tournament' => $tournament]);
    }

}
