<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Checker;
use AIBattle\Game;
use AIBattle\Strategy;
use AIBattle\Tournament;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class TournamentsController extends Controller
{
    /*
     * OLD VERSION WITH tournaments_old.blade.php
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
    }*/

    public function showTournaments() {
        return view('adminPanel/tournaments/tournaments', [
            'runningCount' => Tournament::with('game', 'checker')->where('state', 'running')->count(),
            'preparingCount' => Tournament::with('game', 'checker')->where('state', 'preparing')->count(),
            'closedCount' => Tournament::with('game', 'checker')->where('state', 'closed')->count()
        ]);
    }

    public function getTournaments(Request $request) {
        $tournaments = DB::table('tournaments')
                            ->join('games', 'tournaments.game_id', '=', 'games.id')
                            ->join('checkers', 'tournaments.defaultChecker', '=', 'checkers.id')
                            ->select(   'tournaments.id as id',
                                        'tournaments.name as name',
                                        'tournaments.state as state',
                                        'games.id as gameId',
                                        'games.name as gameName',
                                        'checkers.id as checkerId',
                                        'checkers.name as checkerName'
                            );


        return Datatables::of($tournaments)
                ->filter(function ($query) use (&$request) {

                    $map = array(
                        "gameId" => "games.id",
                        "checkerId" => "checkers.id",
                        "state" => "tournaments.state"
                    );

                    foreach ($map as $key => $value) {
                        if ($request->has($key)) {
                            $query->where($value, '=', $request->get($key));
                        }
                    }
                })
                ->editColumn('name', function($data) {
                    return '<a href="' . url('/adminPanel/tournaments', [$data->id]) . '" role="button">' . $data->name . '</a>';
                })
                ->editColumn('state', function($data) {
                    return trans('adminPanel/tournaments.tournaments_' . $data->state);
                })
                ->editColumn('gameName', function($data) {
                    return '<a href="' . url('/adminPanel/games', [$data->gameId]) . '" role="button">' . $data->gameName . '</a>';
                })
                ->editColumn('checkerName', function($data) {
                    return '<a href="' . url('/adminPanel/checkers', [$data->checkerId]) . '" role="button">' . $data->checkerName . '</a>';
                })
                ->make(true);
    }

    public function showCreateTournamentForm() {

        $games = Game::has('checkers')->get();

        return view('adminPanel/tournaments/tournamentForm', [
            'mode' => 'create',
            'tournamentCount' => count(Tournament::all()) + 1,
            'games' => $games,
            'checkers' => count($games) > 0 ? $games->first()->checkers : new Collection(),
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
            'games' => Game::has('checkers')->get(),
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

    public function showUsersStrategies($id) {
        $tournament = Tournament::findOrFail($id);
        $strategies = Strategy::with('user')->where('tournament_id', $id)->orderBy('id', 'desc')->simplePaginate(25);

        return view('adminPanel/tournaments/userStrategies', [
            'tournament' => $tournament,
            'strategies' => $strategies
        ]);
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
