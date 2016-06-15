<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Checker;
use AIBattle\Game;
use AIBattle\Helpers\GameArchive;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Yajra\Datatables\Datatables;

class CheckersController extends Controller
{
    public function showCheckers() {
        return view('adminPanel/checkers/checkers', ['checkers' => Checker::all()->count()]);
    }

    public function checkersTable() {
        $checkers = DB::table('checkers')->join('games as g', 'checkers.game_id', '=', 'g.id')
                    ->select(['checkers.id as id', 'checkers.name as checkerName', 'g.id as gameId', 'g.name as gameName']);

        return Datatables::of($checkers)
                ->editColumn('checkerName', function ($data) {
                    return '<a href="' . url('/adminPanel/checkers', [$data->id]) . '" role="button">' . $data->checkerName . '</a>';
                })
                ->editColumn('gameName', function($data) {
                    return '<a href="' . url('/adminPanel/games', [$data->gameId]) . '" "><i class="glyphicon glyphicon-tower"></i> ' . $data->gameName . '</a>';
                })
                ->make(true);
    }
    
    public function showCreateCheckerForm() {
        return view('adminPanel/checkers/checkerForm', ['mode' => 'create', 'checkersCount' => count(Checker::all()) + 1, 'games' => Game::all()]);
    }

    public function showCheckerById($id) {
        $checker = Checker::findOrFail($id);
        $game = $checker->game;

        return view('adminPanel/checkers/showChecker', ['checker' => $checker, 'game' => $game, 'checkerData' => $checker->getCheckerData()]);
    }

    public function showEditCheckerForm($id) {
        $checker = Checker::findOrFail($id);

        return view('adminPanel/checkers/checkerForm', ['mode' => 'edit', 'checker' => $checker, 'games' => Game::all()]);
    }

    public function createChecker(Request $request) {
        $this->validate($request, [
            "game" => "required",
            "name" => "required",
            "checkerSource" => "required",
        ]);

        $checker = new Checker();

        // checking if this FK exist in wild nature
        Game::findOrFail(intval($request->input('game')));

        $checker->game_id = $request->input('game');
        $checker->name = $request->input('name');
        $checker->hasSeed = $request->has('hasSeed') ? 1 : 0;

        $checker->save();
        $request->file('checkerSource')->move(base_path() . '/storage/app/testers/', $checker->id);

        $process= new Process('/bin/bash gccChecker.sh ' . $checker->id, base_path() . '/storage/app/compilers/' , ['PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games']);
        $process->run();

        if (!$process->isSuccessful()) {
            Storage::disk('local')->delete('testers/' . $checker->id);
            $checker->delete();

            throw new ProcessFailedException($process);
        }

        GameArchive::createArchive($checker->game);

        return redirect('adminPanel/checkers');

    }

    public function editChecker(Request $request, $id) {

        $checker = Checker::findOrFail($id);

        if ($request->has('delete')) {

            Storage::disk('local')->delete('testers/' . $checker->id);
            Storage::disk('local')->delete('testers_bin/' . $checker->id);

            $checker->delete();

        } else if ($request->has('update')) {

            // checking if this FK exist in wild nature
            Game::findOrFail(intval($request->input('game')));

            $checker->game_id = $request->input('game');
            $checker->name = $request->input('name');
            $checker->hasSeed = $request->has('hasSeed') ? 1 : 0;

            if ($request->hasFile('checkerSource')) {
                // Необходимо поменять файлы

                // function???
                $request->file('checkerSource')->move(base_path() . '/storage/app/testers/', $checker->id);

                $process= new Process('/bin/bash gccChecker.sh ' . $checker->id, base_path() . '/storage/app/compilers/' , ['PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games']);
                $process->run();

                if (!$process->isSuccessful())
                    throw new ProcessFailedException($process);
            }

            $checker->save();

        } else
            abort(404);

        GameArchive::createArchive($checker->game);

        return redirect('adminPanel/checkers');
    }
}
