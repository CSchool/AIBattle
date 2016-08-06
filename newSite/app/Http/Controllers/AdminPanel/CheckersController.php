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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Yajra\Datatables\Datatables;

class CheckersController extends Controller
{
    public function showCheckers($gameId) {
        $game = Game::findOrFail($gameId);
        return view('adminPanel/games/checkers/checkers', ['checkers' => Checker::where('game_id', $gameId)->count(), 'game' => $game]);
    }

    public function checkersTable(Request $request, $gameId) {

        $game = Game::findOrFail($gameId);
        $checkers = $game->checkers()->select(['id', 'name']);

        return Datatables::of($checkers)
                ->editColumn('name', function($checker) use(&$gameId) {
                    return '<a href="' . url('/adminPanel/games', [$gameId, 'checkers', $checker->id]) . '" role="button">' . $checker->name . '</a>';
                })
                ->make(true);
    }
    
    public function showCreateCheckerForm($gameId) {
        $game = Game::findOrFail($gameId);
        return view('adminPanel/games/checkers/checkerForm', [
            'mode' => 'create',
            'checkersCount' => count(Checker::all()) + 1,
            'games' => Game::all(),
            'game' => $game,
        ]);
    }

    public function showCheckerById($gameId, $checkerId) {
        $game = Game::findOrFail($gameId);
        $checker = Checker::findOrFail($checkerId);

        if ($checker->game->id == $game->id) {
            return view('adminPanel/games/checkers/showChecker', [
                'checker' => $checker,
                'game' => $game,
                'checkerData' => $checker->getCheckerData()
            ]);
        } else {
            abort(403);
        }
    }

    public function showEditCheckerForm($gameId, $checkerId) {
        $game = Game::findOrFail($gameId);
        $checker = Checker::findOrFail($checkerId);

        if ($checker->game->id == $game->id) {
            return view('adminPanel/games/checkers/checkerForm', [
                'mode' => 'edit',
                'checker' => $checker,
                'games' => Game::all(),
                'game' => $game,
            ]);
        } else {
            abort(403);
        }
    }

    public function createChecker(Request $request, $gameId) {
        $this->validate($request, [
            "name" => "required",
            "checkerSource" => "required",
        ]);

        $checker = new Checker();

        // checking if this FK exist in wild nature
        Game::findOrFail($gameId);

        $checker->game_id = $gameId;
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

        return redirect('adminPanel/games/' . $gameId . '/checkers');

    }

    public function editChecker(Request $request, $gameId, $id) {

        $game = Game::findOrFail($gameId);
        $checker = Checker::findOrFail($id);

        if ($request->has('delete')) {

            Storage::disk('local')->delete('testers/' . $checker->id);
            Storage::disk('local')->delete('testers_bin/' . $checker->id);

            $checker->delete();

        } else if ($request->has('update')) {

            $this->validate($request, [
                "name" => "required",
            ]);

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

        return redirect('adminPanel/games/' . $gameId . '/checkers');
    }
}
