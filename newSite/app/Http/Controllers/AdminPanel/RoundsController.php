<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Checker;
use AIBattle\Duel;
use AIBattle\Helpers\DuelsQuery;
use AIBattle\Helpers\RoundMaker;
use AIBattle\Jobs\RunDuel;
use AIBattle\Round;
use AIBattle\Score;
use AIBattle\Strategy;
use AIBattle\Tournament;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\Datatables\Facades\Datatables;

class RoundsController extends Controller
{
    public function showRounds($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        return view('adminPanel/rounds/rounds', ['rounds' => Round::all()->count(), 'tournament' => $tournament]);
    }

    private function calculatePercentage($part, $all) {
        return $all != 0 ? number_format($part / $all * 100, 2) : 0;
    }

    public function roundsTable($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        $rounds = new Collection(
            DB::select('SELECT r1.id AS id, r1.name AS name, r1.date AS date, 
                        r1.tournament_id AS tournament_id, r2.name AS prevName, r2.id AS prevRound,
                        r1.visible AS visible
                        FROM rounds as r1 LEFT OUTER JOIN rounds as r2 ON r1.previousRound = r2.id 
                        WHERE r1.tournament_id = ' . $tournamentId)
        );

        return Datatables::of($rounds)
                ->editColumn('name', function($round) {
                    $url = url('adminPanel/tournaments/' . $round->tournament_id . '/rounds/' . $round->id);
                    return '<a href="' . $url . '">' . $round->name . '</a>';
                })
                ->addColumn('rounds', function($round) {
                    $url = url('adminPanel/tournaments/' . $round->tournament_id . '/rounds/' . $round->id . '/results');
                    return '<a href="' . $url . '" class="btn-xs btn-info"><i class="glyphicon glyphicon-flag"></i> ' . trans('shared.show') . '</a>';
                })
                ->addColumn('prev', function($round) {
                    if ($round->prevName == null) {
                        return trans('adminPanel/rounds.roundsPrevNA');
                    } else {
                        $url = url('adminPanel/tournaments/' . $round->tournament_id . '/rounds/' . $round->prevRound);
                        return '<a href="' . $url . '">' . $round->prevName . '</a>';
                    }
                })
                ->addColumn('state', function ($round) {
                    $duels = Duel::where('round', $round->id);

                    $allDuels = $duels->count();
                    $doneDuels = $duels->where('status', '<>', 'W')->count();

                    if ($allDuels != $doneDuels) {
                        $percentage = $this->calculatePercentage($doneDuels, $allDuels);
                        $text = $percentage . '%';

                        return '<div class="progress"><div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="' . $doneDuels . '" aria-valuemin="0" aria-valuemax="' . $allDuels . '" style="width:' . $percentage . '%"> ' . $text . '</div></div>';

                    } else {
                        $successDuels = $duels->whereIn('status', ['TIE', 'WIN 1', 'WIN 2'])->count();

                        if ($successDuels == $allDuels) {
                            return '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' . $doneDuels . '" aria-valuemin="0" aria-valuemax="' . $allDuels . '" style="width:100%"> 100% </div></div>';
                        } else {
                            $successDiv = '<div class="progress-bar progress-bar-success" role="progressbar" style="width:'. $this->calculatePercentage($successDuels, $allDuels) . '%"> ' . $successDuels . ' </div>';
                            $failedDiv = '<div class="progress-bar progress-bar-danger" role="progressbar" style="width:'. $this->calculatePercentage($allDuels - $successDuels, $allDuels) . '%"> ' . ($allDuels - $successDuels) . ' </div>';
                            return '<div class="progress">' . $failedDiv . $successDiv . '</div>';
                        }
                    }
                })
                ->editColumn('visible', function ($round) {
                    if ($round->visible == 1) {
                        return trans('adminPanel/rounds.roundsVisibleRound');
                    } else {
                        return trans('adminPanel/rounds.roundsInvisibleRound');
                    }
                })
                ->make(true);
    }

    public function showCreateRound($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        $checkers = $tournament->game->checkers;
        $rounds = Round::where('tournament_id', $tournamentId)->select('id', 'name')->orderBy('id', 'desc')->get();

        return view('adminPanel/rounds/createRound', ['tournament' => $tournament, 'checkers' => $checkers, 'prevRounds' => $rounds]);
    }

    public function showRound($tournamentId, $roundId) {
        $tournament = Tournament::findOrFail($tournamentId);
        $round = Round::findOrFail($roundId);
        $checker = Checker::findOrFail($round->checker_id);

        $prevRoundName = $round->previousRound == -1 ? trans('adminPanel/rounds.roundsPrevNA') : Round::findOrFail($round->previousRound)->name;
        
        
        return view('adminPanel/rounds/showRound', [
            'round' => $round, 
            'tournament' => $tournament, 
            'checker' => $checker,
            'prevRoundName' => $prevRoundName,
        ]);
    }

    private function getPossiblePlayerTuple($username, $strategyId, $playerScore) {
        return [
            $username,
            $strategyId,
            $playerScore,
            '<button data-id="' . $strategyId . '" data-player="' . $username . '" data-score="' . $playerScore . '" class="btn-xs btn-success"><i class="glyphicon glyphicon-ok"></i> ' . trans('adminPanel/rounds.createRoundAddPlayer') . '</button>'
        ];
    }

    public function getPossiblePlayers($tournamentId, $previousRound = -1) {
        $query = Strategy::with(['user' => function($query) {
            $query->select('id', 'username')->get();
        }])
        ->where('tournament_id', $tournamentId)->where('status', 'ACT')->select('id', 'user_id')->get();

        $data = array();

        $scores = new Collection(
            DB::select('SELECT SUM(score) AS score, users.username AS username, users.id AS user_id FROM scores INNER JOIN strategies ON strategies.id = strategy_id INNER JOIN users ON strategies.user_id = users.id WHERE round_id = ' . $previousRound . ' GROUP BY username')
        );

        foreach ($query as $key => $value) {

            $playerScoreKey = $scores->search(function ($item, $scoreKey) use (&$value) {
                return $item->user_id == $value->user->id;
            });

            if ($previousRound == -1) {
                array_push($data, $this->getPossiblePlayerTuple($value->user->username, $value->id, -1));
            } else if ($playerScoreKey !== false) {
                $playerScore = $scores[$playerScoreKey]->score;
                array_push($data, $this->getPossiblePlayerTuple($value->user->username, $value->id, $playerScore));
            }
        }

        $result = new Collection($data);
        return $result->toJson(JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function showRoundResults($tournamentId, $roundId) {
        $tournament = Tournament::findOrFail($tournamentId);
        $round = Round::findOrFail($roundId);

        if ($round->tournament_id == $tournamentId) {
            return view('adminPanel/rounds/roundResults', ['round' => $round]);
        } else {
            abort(403);
        }
    }

    public function showRoundScoreTable($roundId) {

        $round = Round::findOrFail($roundId);
        $scores = new Collection(DB::select('SELECT SUM(score) AS score, users.username AS strategy  FROM scores INNER JOIN strategies ON strategies.id = strategy_id INNER JOIN users ON strategies.user_id = users.id WHERE round_id = ' . $roundId . ' GROUP BY strategy'));

        return Datatables::of($scores)->make(true);
    }

    public function showRoundDuels(Request $request, $roundId) {
        $round = Round::findOrFail($roundId);
        $tournament = $round->tournament;
        $tournamentId = $tournament->id;
        $userId = Auth::user()->id;
        $userName = Auth::user()->username;


        $data = DuelsQuery::data($round->id, $tournament->game->id, intval($userId), intval($tournament->id));

        return Datatables::of($data)
            ->filter(function($query) use(&$request) {
                // custom filtering for our purposes
                if ($request != null) {
                    if ($request->has('user1')) {
                        $query->where('usr1.username', '=', $request->get('user1'));
                    }

                    if ($request->has('user2')) {
                        $query->where('usr2.username', '=', $request->get('user2'));
                    }
                }
            })
            ->editColumn('status', function($data) use(&$userName, &$tournament) {
                // make sure of status

                $data = (array)$data;

                $statusArray = explode(' ', trim($data['status']));
                $linkClass = "info";

                if (count($statusArray) > 1) {
                    if ($data['user1'] == $userName || $data['user2'] == $userName) {
                        if (str_contains($data['status'], 'WIN')) {
                            if ($data['user' . $statusArray[1]] == $userName) {
                                $linkClass = "success";
                            } else {
                                $linkClass = "danger";
                            }
                        } else {
                            // something wrong, check if it our fail or not
                            if ($data['user' . $statusArray[1]] == $userName) {
                                // something wrong with us
                                $linkClass = "danger";
                            }
                        }
                    }
                } else {
                    if ($statusArray[0] == "TIE") {
                        $linkClass = "primary";
                    }
                }

                $action = '#';
                if ($statusArray[0] != "W") {
                    $action = action('DownloadController@downloadLog', [$tournament->id, $data['id']]);
                }

                return '<a href="' . $action . '" class="btn-xs btn-' . $linkClass . '"><i class="glyphicon glyphicon-download-alt"></i> ' . $data['status'] . '</a>';
            })
            ->editColumn('hasVisualizer', function($data) use(&$tournamentId) {

                $data = (array)$data;
                $statusArray = explode(' ', trim($data['status']));
                $href = "#";

                if ($statusArray[0] != "W") {
                    $href = url('tournaments/' . $tournamentId . '/training/' . $data['id']);
                }

                return '<a href="' . $href . '"  target="_blank" class="btn-xs btn-warning"><i class="glyphicon glyphicon-play"></i> ' . trans('tournaments/strategies.trainingViewGame') . '</a>';
            })
            ->make(true);
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
            } else if (count($request["data"]["players"]) < 2) {
                array_push($errors, "Amount of accepted players is lower than 2!");
            }

            if ($tournament->checker->hasSeed == 1) {
                $isSeedNumber = is_numeric($request["data"]["seed"]);

                if ($isSeedNumber === false) {
                    array_push($errors, "'" . $request["data"]["seed"] . "' is not numeric seed!");
                }
            }

            if (empty($request["data"]["name"])) {
                array_push($errors, "Round name is empty!");
            }

            if (count($errors) > 0) {
                return response()->json(['status' => 'err', 'errors' => $errors]);
            } else {

                $duels = RoundMaker::makeRound($request["data"], $tournamentId);

                $job = new RunDuel($duels);
                $job->onQueue('training');

                $this->dispatch($job);

                return response()->json(['status' => 'ok', 'tournamentId' => $tournamentId]);
            }

        } else {
            return response()->json(['status' => 'err', 'errors' => ['There is no tournament with id = ' . $tournamentId]]);
        }
    }

    public function changeRoundState(Request $request, $tournamentId, $roundId) {
        $tournament = Tournament::findOrFail($tournamentId);
        $round = Round::findOrFail($roundId);

        if ($round->tournament->id == $tournament->id) {
            $round->visible = $round->visible == 1 ? 0 : 1;
            $round->save();

            return redirect('adminPanel/tournaments/' . $round->tournament->id . '/rounds');
        } else {
            abort(404);
        }
    }
}
