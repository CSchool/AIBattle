<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Checker;
use AIBattle\Game;
use AIBattle\Tournament;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TournamentsController extends Controller
{
    //
    public function showTournaments() {

        $headerCount = 5;

        return view('adminPanel/tournaments/tournaments', [
            'runningTournaments' => Tournament::with('game', 'checker')->where('state', 'running')->orderBy('id', 'desc')->take($headerCount)->get(),
            'preparingTournaments' => Tournament::with('game', 'checker')->orderBy('id', 'desc')->where('state', 'preparing')->take($headerCount)->get(),
            'closedTournaments' => Tournament::with('game', 'checker')->orderBy('id', 'desc')->where('state', 'closed')->take($headerCount)->get(),
            'runningCount' => Tournament::with('game', 'checker')->orderBy('id')->where('state', 'running')->count(),
            'preparingCount' => Tournament::with('game', 'checker')->orderBy('id')->where('state', 'preparing')->count(),
            'closedCount' => Tournament::with('game', 'checker')->orderBy('id')->where('state', 'closed')->count()
        ]);
    }

    public function showCreateTournamentForm() {
        return view('adminPanel/tournaments/tournamentForm', [
            'mode' => 'create',
            'tournamentCount' => count(Tournament::all()) + 1,
            'games' => Game::all(),
            'checkers' => Game::groupBy('id')->first()->checkers()->getResults() // yep, get first game at DB
        ]);
    }

    public function showTournamentById($id) {
        $tournament = Tournament::findOrFail($id);

        return view('adminPanel/tournaments/showTournament', ['tournament' => $tournament, 'user' => Auth::user()]);
    }

    public function showEditTournamentForm($id) {
        $tournament = Tournament::findOrFail($id);

        return view('adminPanel/tournaments/tournamentForm', [
            'mode' => 'edit',
            'tournament' => $tournament,
            'games' => Game::all(),
            'checkers' => $tournament->game->checkers
        ]);
    }

    public function showExtendedTournamentTable($state) {
        switch ($state) {
            case "running":
                return view('adminPanel/tournaments/extendedTable', [
                    'tournaments' => Tournament::with('game', 'checker')->where('state', 'running')->orderBy('id')->simplePaginate(10),
                    'title' => 'Running'
                ]);
                break;
            case "preparing":
                return view('adminPanel/tournaments/extendedTable', [
                    'tournaments' => Tournament::with('game', 'checker')->where('state', 'preparing')->orderBy('id')->simplePaginate(10),
                    'title' => 'Preparing'
                ]);
                break;
            case "closed":
                return view('adminPanel/tournaments/extendedTable', [
                    'tournaments' => Tournament::with('game', 'checker')->where('state', 'closed')->orderBy('id')->simplePaginate(10),
                    'title' => 'Closed'
                ]);
                break;
            default:
                abort(404);
        }
    }

    public function createTournament(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:32',
            'description' => 'required',
            'game' => 'required',
            'state' => 'required',
            'checker' => 'required',
        ]);

        $tournament = new Tournament();

        $tournament->name = $request->input('name');
        $tournament->description = $request->input('description');

        // checking if this FK exist in wild nature
        Game::findOrFail(intval($request->input('game')));
        Checker::findOrFail(intval($request->input('checker')));

        $tournament->game_id = $request->input('game');
        $tournament->defaultChecker = $request->input('checker');

        $tournament->state = $request->input('state');

        $tournament->save();

        return redirect('adminPanel/tournaments');
    }

    public function editTournament(Request $request, $id) {
        $tournament = Tournament::findOrFail($id);

        if ($request->has('delete')) {
            $tournament->delete();

            return redirect('adminPanel/tournaments');
        } elseif ($request->has('update')) {
            $tournament->name = $request->input('name');
            $tournament->description = $request->input('description');

            // checking if this FK exist in wild nature
            Game::findOrFail(intval($request->input('game')));
            Checker::findOrFail(intval($request->input('checker')));

            $tournament->game_id = $request->input('game');
            $tournament->defaultChecker = $request->input('checker');

            $tournament->state = $request->input('state');

            $tournament->save();

            return redirect('adminPanel/tournaments');
        } else
            abort(404);
    }

   // ajax methods

    public function getCheckersByGameId($id) {
        $game = Game::findOrFail($id);
        return $game->checkers()->getResults()->toJson();
    }
}
