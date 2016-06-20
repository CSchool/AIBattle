<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Checker;
use AIBattle\Round;
use AIBattle\Strategy;
use AIBattle\Tournament;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Yajra\Datatables\Facades\Datatables;

class RoundsController extends Controller
{
    public function showRounds($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        return view('adminPanel/rounds/rounds', ['rounds' => Round::all()->count(), 'tournament' => $tournament]);
    }

    public function showCreateRound($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        $checkers = $tournament->game->checkers;

        return view('adminPanel/rounds/createRound', ['tournament' => $tournament, 'checkers' => $checkers]);
    }

    public function getPossiblePlayers($tournamentId, $previousRound = -1) {
        $query = Strategy::with(['user' => function($query) {
            $query->select('id', 'username')->get();
        }])
        ->where('tournament_id', $tournamentId)->where('status', 'ACT')->select('id', 'user_id')->get();

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

    public function createRound(Request $request, $tournamentId) {

        $tournament = Tournament::where('id', $tournamentId)->first();
        $errors = array();

        if ($tournament != null) {
            $checkerId = $request["data"]["checker"];
            $checker = Checker::where('id', $checkerId)->first();

            if (empty($checker)) {
                array_push($errors, "Checker with id " . $checkerId . " doesn't exists!");
            } elseif (!$tournament->game->checkers->contains($checker)) {
                array_push($errors, "Checker with id " . $checkerId . " is not allowed for game '" . $tournament->game->name . "'");
            }

            if (!array_key_exists("players", (array)$request["data"])) {
                array_push($errors, "Accepted players list is empty!");
            }

            $isSeedNumber = is_numeric($request["data"]["seed"]);

            if ($isSeedNumber === false) {
                array_push($errors, "'" . $request["data"]["seed"] . "' is not numeric seed!");
            }

            if (empty($request["data"]["name"])) {
                array_push($errors, "Round name is empty!");
            }

            if (count($errors) > 0) {
                return response()->json(['status' => 'err', 'errors' => $errors]);
            } else {
                return response()->json(['status' => 'ok']);
            }

        } else {
            return response()->json(['status' => 'err', 'errors' => ['There is no tournament with id = ' . $tournamentId]]);
        }
    }
}
