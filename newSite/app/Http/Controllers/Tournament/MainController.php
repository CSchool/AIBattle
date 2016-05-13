<?php

namespace AIBattle\Http\Controllers\Tournament;

use AIBattle\Tournament;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;

class MainController extends Controller
{

    public function showTournament($id) {

        $tournament = Tournament::findOrFail($id);
        return view('tournaments/main', ['tournament' => $tournament]);
    }
}
