<?php

namespace AIBattle\Http\Controllers\Tournament;

use AIBattle\Duel;
use AIBattle\Helpers\CompilerProcess;
use AIBattle\Helpers\DuelsQuery;
use AIBattle\Helpers\EncodingConvert;
use AIBattle\Jobs\RunDuel;
use AIBattle\Strategy;
use AIBattle\Tournament;
use AIBattle\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;

class StrategiesController extends Controller
{
    private $allowedCompilers = ['gcc', 'fpc'];

    public function showStrategies($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        $activeStrategy = $tournament->strategies()->where('user_id', Auth::user()->id)->where('status', 'ACT')->first();

        return view('tournaments/strategies/showStrategies', [
            'tournament' => $tournament,
            'strategies' => $tournament->strategies()->where('user_id', Auth::user()->id)->count(), 
            'activeStrategy' => $activeStrategy,
        ]);
    }

    public function showTraining($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        return view('tournaments/strategies/training', [
            'tournament' => $tournament,
        ]);

    }

    private function popoverTag($text, $isError = false) {
        $sign = $isError ? 'glyphicon-fire' : 'glyphicon-info-sign';
        return "<a data-toggle=\"popover\" data-trigger=\"hover\" data-content=\"$text\"><span class=\"glyphicon $sign\"></span></a>";
    }

    public function getTournamentStrategiesTable($tournamentId) {
        $strategies = Tournament::findOrFail($tournamentId)
                        ->strategies()
                        ->where('user_id', Auth::user()->id)
                        ->where('status', '!=', 'ACT')
                        ->select(['id', 'name', 'description', 'status', 'tournament_id']);

        return Datatables::of($strategies)
            ->removeColumn('description')
            ->removeColumn('tournament_id')
            ->editColumn('name', function ($strategy) {
                $additionalText = '';

                if ($strategy->status == 'ERR')
                    $additionalText = $this->popoverTag(trans('tournaments/strategies.showStrategiesFailedCompilation'), true);
                elseif (!empty($strategy->description))
                    $additionalText = $this->popoverTag(strip_tags($strategy->description));

                return '<a href=' . url('tournaments/' . $strategy->tournament_id . '/strategies', [$strategy->id]) . '>' . $strategy->name . '</a> ' . $additionalText;
            })
            ->setRowId('id')
            ->setRowClass(function ($strategy) {
                switch ($strategy->status) {
                    case 'ACT':
                        return 'warning';
                        break;
                    case 'OK':
                        return 'success';
                        break;
                    case 'ERR':
                        return 'danger';
                        break;
                    default:
                        return 'default';
                        break;
                }
            })
            ->make(true);
    }

    public function getTrainingStrategiesForCompetition($tournamentId) {

        $strategies = Strategy::with(['User' => function($query) {
            $query->select('id','username');
        }])
            ->where('tournament_id', $tournamentId)
            ->where('status', '!=', 'ERR')
            ->select('strategies.id', 'strategies.name', 'strategies.user_id', 'strategies.tournament_id', 'strategies.status');

        $userId = Auth::user()->id;

        return Datatables::of($strategies)
            ->removeColumn('tournament_id')
            ->removeColumn('user_id')
            ->removeColumn('status')
            ->editColumn('name', function ($strategy) use(&$userId) {
                if ($strategy->user_id == $userId)
                    return $strategy->name;
                else
                    return trans('tournaments/strategies.trainingStrategy', ['id' => $strategy->id]);
            })
            ->addColumn('action', function($strategy) {
                return '<a href="' . action('Tournament\StrategiesController@startTraining', [$strategy->tournament_id, $strategy->id]) . '" class="btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> ' . trans('tournaments/strategies.trainingStartCompetition') . '</a>';
            })
            ->setRowId('id')
            ->setRowClass(function ($strategy) use(&$userId) {
                if ($strategy->user_id == $userId && $strategy->status == 'ACT')
                    return 'warning';
                else
                    return 'default';
            })
            ->make(true);

    }

    public function getTrainingDuels(Request $request, $tournamentId) {

        $tournament = Tournament::findOrFail($tournamentId);
        $userId = Auth::user()->id;
        $userName = Auth::user()->username;

        $data = DuelsQuery::data(-1, $tournament->game->id, $userId, $tournamentId);

        return Datatables::of($data)
            ->filter(function($query) use(&$request) {

                // custom filtering for our purposes

                if ($request->has('user1')) {
                    $query->where('usr1.username', '=', $request->get('user1'));
                }

                if ($request->has('user2')) {
                    $query->where('usr2.username', '=', $request->get('user2'));
                }


            })
            ->editColumn('status', function($data) use(&$userName, &$tournament) {
                // make sure of status

                $data = (array)$data;

                $statusArray = explode(' ', $data['status']);
                $linkClass = "info";

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

                return '<a href="' . action('DownloadController@downloadLog', [$tournament->id, $data['id']]) . '" class="btn-xs btn-' . $linkClass . '"><i class="glyphicon glyphicon-download-alt"></i> ' . $data['status'] . '</a>';
            })
            ->editColumn('hasVisualizer', function($data) {
                return '<a href="#" class="btn-xs btn-warning"><i class="glyphicon glyphicon-play"></i> ' . trans('tournaments/strategies.trainingViewGame') . '</a>';
            })
            ->make(true);
    }

    public function showCreateStrategyForm($tournamentId) {
        $tournament = Tournament::findOrFail($tournamentId);

        return view('tournaments/strategies/strategyForm', [
            'mode' => 'create',
            'tournament' => $tournament,
            'backLink' => url('tournaments/'. $tournamentId .'/strategies')
        ]);
    }

    private function showEditStrategyForm($tournamentId, $strategyId, $backLink) {
        $tournament = Tournament::findOrFail($tournamentId);
        $strategy = Strategy::findOrFail($strategyId);

        if (
            ($strategy->tournament == $tournament && $strategy->game == $tournament->game && $strategy->user == Auth::user())
            || User::isAdmin()
        ) {
            $mode = User::isAdmin() ? 'editStrategyAdmin' : 'editStrategy';

            return view('tournaments/strategies/strategyForm', [
                'mode' => $mode,
                'tournament' => $tournament,
                'strategy' => $strategy,
                'backLink' => $backLink,
            ]);
        }
        else
            abort(404);
    }

    public function showEditStrategyFormPublic($tournamentId, $strategyId) {
        return $this->showEditStrategyForm($tournamentId, $strategyId, url('tournaments/'. $tournamentId .'/strategies'));
    }

    public function showEditStrategyFormAdminPanel($tournamentId, $strategyId) {
        return $this->showEditStrategyForm($tournamentId, $strategyId, url('adminPanel/tournaments/' . $tournamentId . '/strategies'));
    }

    private function showStrategyProfile($tournamentId, $strategyId, $view, $strategyBackLink, $strategyEditLink, $strategySetLinkActive) {
        $tournament = Tournament::findOrFail($tournamentId);
        $strategy = Strategy::findOrFail($strategyId);

        if (
            ($strategy->tournament == $tournament && $strategy->game == $tournament->game && $strategy->user == Auth::user())
            || User::isAdmin()
        ) {

            // iso-8859-1
            $codeEncoding = EncodingConvert::getFileEncoding('executions/' . $strategy->id);
            $logEncoding = EncodingConvert::getFileEncoding('compilelogs/' . $strategy->id . '.txt');

            $code = Storage::disk('local')->get('/executions/' . $strategy->id);
            $log = Storage::disk('local')->get('/compilelogs/' . $strategy->id . '.txt');


            if ($codeEncoding != 'UTF-8')
                $code = mb_convert_encoding($code, 'utf-8', $codeEncoding);

            if ($logEncoding != 'UTF-8')
                $log = mb_convert_encoding($log, 'utf-8', $logEncoding);

            return view($view , [
                'tournament' => $tournament,
                'strategy' => $strategy,
                'strategyLanguage' => Strategy::getLanguageByChecker($strategy->language),
                'strategyPrismStyle' => Strategy::getLanguagePrismClass($strategy->language),
                'strategyCode' => $code,
                'strategyErrorLog' => $log,
                'strategyBackLink' => $strategyBackLink,
                'strategyEditLink' => $strategyEditLink,
                'strategySetActiveLink' => $strategySetLinkActive,
                'strategyOwner' => $strategy->user_id == Auth::user()->id,
            ]);

        }
        else
            abort(403);
    }

    public function showStrategyProfilePublic($tournamentId, $strategyId) {
        return $this->showStrategyProfile(
            $tournamentId,
            $strategyId,
            'tournaments/strategies/strategyProfile',
            url('tournaments/' . $tournamentId . '/strategies'),
            url('tournaments/' . $tournamentId . '/strategies/edit/' . $strategyId),
            url('tournaments/' . $tournamentId . '/strategies/' . $strategyId . '/setActive')
        );
    }

    public function showStrategyProfileAdminPanel($tournamentId, $strategyId) {
        return $this->showStrategyProfile(
            $tournamentId,
            $strategyId,
            'tournaments/strategies/strategyProfile',
            url('adminPanel/tournaments/' . $tournamentId . '/strategies'),
            url('adminPanel/tournaments/' . $tournamentId . '/strategies/edit/' . $strategyId),
            url('adminPanel/tournaments/' . $tournamentId . '/strategies/' . $strategyId . '/setActive')
        );
    }

    private function setStrategyActive($tournamentId, $strategyId, $backLink) {
        $tournament = Tournament::findOrFail($tournamentId);
        $strategy = Strategy::findOrFail($strategyId);

        if (
            ($strategy->tournament == $tournament && $strategy->game == $tournament->game && $strategy->user == Auth::user())
            || User::isAdmin()
        ) {
            $strategy->setActive(Auth::user()->id, $tournament->id);
            $strategy->save();

            return redirect($backLink);
        }
        else
            abort(404);

    }

    public function setStrategyActivePublic($tournamentId, $strategyId) {
        return $this->setStrategyActive($tournamentId, $strategyId, 'tournaments/' . $tournamentId . '/strategies');
    }

    public function setStrategyActiveAdminPanel($tournamentId, $strategyId) {
        return $this->setStrategyActive($tournamentId, $strategyId, 'adminPanel/tournaments/' . $tournamentId . '/strategies');
    }

    public function createStrategy(Request $request, $id)
    {
        $tournament = Tournament::findOrFail($id);

        // have files to compile

        if ($request->has('loadChoose')) {
            if ($request->input('loadChoose') == "fileLoad")
                $this->validate($request, [
                    'strategySource' => 'required',
                ]);
            elseif ($request->input('loadChoose') == 'textLoad')
                $this->validate($request, [
                    'strategyText' => 'required',
                ]);
            else
                abort(404, 'Can\'t determine way of strategy loading');
        } else
            abort(404, 'Can\'t determine way of strategy loading');

        // have compiler

        if (!in_array($request->input('compiler'), $this->allowedCompilers))
            abort(404, 'Can\'t find compiler!');

        // make entry in DB
        $strategy = new Strategy();

        $strategy->name = $request->input('name');
        $strategy->description = $request->input('description');
        $strategy->language = $request->input('compiler');

        $strategy->user_id = Auth::user()->id;
        $strategy->game_id = $tournament->game->id;
        $strategy->tournament_id = $tournament->id;

        $strategy->save();

        if (empty($strategy->name))
            $strategy->name = "Strategy" . $strategy->id;

        // saving file
        if ($request->input('loadChoose') == 'textLoad')
            Storage::disk('local')->put('executions/' . $strategy->id,
                $request->input('strategyText') );

        else if ($request->input('loadChoose') == 'fileLoad' && $request->hasFile('strategySource'))
            $request->file('strategySource')->move(base_path() . '/storage/app/executions/', $strategy->id);

        // it's compilation time!

        // linux solution
        $process = CompilerProcess::getProcess($strategy->language . '.sh', $strategy->id);

        $process->run();

        if (!$process->isSuccessful())
            $strategy->status = 'ERR';
        else {
            $strategy->setActive(Auth::user()->id, $tournament->id);
        }

        $strategy->save();

        return redirect('tournaments/' . $tournament->id . '/strategies');
    }

    private function editStrategy(Request $request, $tournamentId, $strategyId, $backLink) {
        $tournament = Tournament::findOrFail($tournamentId);
        $strategy = Strategy::findOrFail($strategyId);

        if (
            ($strategy->tournament == $tournament && $strategy->game == $tournament->game && $strategy->user == Auth::user())
            || User::isAdmin()
        ) {

            if ($request->has('update')) {
                $strategy->name = $request->input('name');
                $strategy->description = $request->input('description');

                $strategy->save();
            } elseif ($request->has('delete')) {
                if (User::isAdmin()) {

                    Storage::disk('local')->delete([
                            'compilelogs/' . $strategy->id . '.txt',
                            'executions/' . $strategy->id,
                            'executions_bin/' . $strategy->id]
                    );

                    $strategy->delete();
                }
                else
                    abort(404);
            }

            return redirect($backLink);
        }
        else
            abort(404);
    }

    public function editStrategyPublic(Request $request, $tournamentId, $strategyId) {
        return $this->editStrategy($request, $tournamentId, $strategyId, 'tournaments/' . $tournamentId . '/strategies');
    }

    public function editStrategyAdminPanel(Request $request, $tournamentId, $strategyId) {
        return $this->editStrategy($request, $tournamentId, $strategyId, 'adminPanel/tournaments/' . $tournamentId . '/strategies');
    }

    private function startTrainingDuel($strategy1, $strategy2) {
        $duel = new Duel();

        $duel->strategy1 = $strategy1->id;
        $duel->strategy2 = $strategy2->id;
        $duel->status = 'W';

        $duel->save();

        $job = (new RunDuel($strategy1, $strategy2, $duel))->onQueue('training');
        $this->dispatch($job);
    }

    public function startTraining($tournamentId, $strategyId) {
        $tournament = Tournament::findOrFail($tournamentId);
        $enemyStrategy = Strategy::findOrFail($strategyId);

        if ($enemyStrategy->tournament_id != $tournament->id && $tournament->game_id != $enemyStrategy->game_id) {
            abort(403);
        } else {

            $playerStrategy = Strategy::where('tournament_id', $tournamentId)
                                        ->where('user_id', Auth::user()->id)
                                        ->where('status', 'ACT')
                                        ->firstOrFail();

            $this->startTrainingDuel($playerStrategy, $enemyStrategy);
            $this->startTrainingDuel($enemyStrategy, $playerStrategy);
        }

        return redirect('tournaments/' . $tournamentId . '/training');
    }
}
