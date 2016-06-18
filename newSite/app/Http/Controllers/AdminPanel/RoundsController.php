<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Round;
use AIBattle\Strategy;
use AIBattle\Tournament;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Yajra\Datatables\Facades\Datatables;

class RoundsController extends Controller
{
    public function showRounds($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        return view('adminPanel/rounds/rounds', ['rounds' => Round::all()->count(), 'tournament' => $tournament]);
    }

    public function showCreateRound($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        return view('adminPanel/rounds/createRound', ['tournament' => $tournament]);
    }

    public function getPossiblePlayers($tournamentId, $previousRound = -1) {


        $query = Strategy::with(['user' => function($query) {
            $query->select('id', 'username')->get();
        }])
        ->where('tournament_id', $tournamentId)->where('status', 'ACT')->select('id', 'user_id')->get();


        /*
        return Datatables::of($query)
                ->addColumn('action', function($data) {
                    return '<button data-id="' . $data->id .'" data-player="' . $data->user->username . '" class="btn-xs btn-success"><i class="glyphicon glyphicon-ok"></i> ' . trans('adminPanel/rounds.createRoundAddPlayer') . '</button>';
                })
                ->make(true);
        */

        $data = array();

        foreach ($query as $key => $value) {
            array_push($data, [
                $value->user->username,
                $value->id,
                '<button data-id="' . $value->id . '" data-player="' . $value->user->username . '" class="btn-xs btn-success"><i class="glyphicon glyphicon-ok"></i> ' . trans('adminPanel/rounds.createRoundAddPlayer') . '</button>'
            ]);
        }

        $result = new Collection($data);

        return $result->toJson(JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
