<?php

namespace AIBattle\Http\Controllers\Tournament;

use AIBattle\Helpers\DuelsDataTable;
use AIBattle\Helpers\DuelsQuery;
use AIBattle\Helpers\RoundTable;
use AIBattle\Round;
use AIBattle\Tournament;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class RoundsController extends Controller
{
    public function showRounds($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);
        $rounds = Round::where('tournament_id', $tournamentId)->where('visible', 1)->get();

        return view('tournaments/rounds/roundList', [
            'tournament' => $tournament,
            'rounds' => $rounds
        ]);
    }

    public function showRoundResult($tournamentId, $roundId) {
        $tournament = Tournament::findOrFail($tournamentId);
        $round = Round::findOrFail($roundId);

        if ($round->tournament_id == $tournamentId && $round->visible == 1) {
            return view('tournaments/rounds/results', [
                'round' => $round,
                'tournament' => $tournament,
                'roundTableUrl' => url('tournaments', [$tournament->id, 'rounds', $round->id, 'roundTable']),
                'mode' => 'users',
            ]);
        } else {
            abort(403);
        }
    }

    public function showRoundTable($tournamentId, $roundId) {
        $tournament = Tournament::findOrFail($tournamentId);
        $round = Round::findOrFail($roundId);

        if ($round->tournament_id == $tournamentId && $round->visible == 1) {
            $roundTable = new RoundTable($roundId);

            return view('tournaments/rounds/roundTable', [
                'round' => $round,
                'tournament' => $tournament,
                'players' => $roundTable->getPlayers(),
                'roundTable' => $roundTable->getRoundTable(),
            ]);
        } else {
            abort(403);
        }
    }

    public function showRoundDuels(Request $request, $tournamentId, $roundId) {
        $tournament = Tournament::findOrFail($tournamentId);
        $round = Round::findOrFail($roundId);
        $userId = Auth::user()->id;

        $data = DuelsQuery::data($round->id, $tournament->game->id, intval($userId), intval($tournament->id));

        return DuelsDataTable::transform($data, $tournament);
    }

    public function showRoundResultsTable($tournamentId, $roundId) {
        $roundTable = new RoundTable($roundId);
        return Datatables::of($roundTable->getScores())->make(true);
    }
}
