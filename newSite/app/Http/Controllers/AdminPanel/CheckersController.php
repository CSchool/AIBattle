<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Checker;
use AIBattle\Game;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CheckersController extends Controller
{
    public function showCheckers() {
        return view('adminPanel/checkers/checkers', ['checkers' => Checker::orderBy('id')->simplePaginate(10), 'user' => Auth::user()]);
    }
    
    public function showCreateCheckerForm() {
        return view('adminPanel/checkers/checkerForm', ['mode' => 'create', 'checkersCount' => count(Checker::all()) + 1, 'games' => Game::all(), 'user' => Auth::user()]);
    }

    public function showCheckerById($id) {
        $checker = Checker::findOrFail($id);
        $game = Game::findOrFail($checker->game);

        return view('adminPanel/checkers/showChecker', ['checker' => $checker, 'game' => $game, 'checkerData' => $checker->getCheckerData(), 'user' => Auth::user()]);
    }

    public function showEditCheckerForm($id) {
        $checker = Checker::findOrFail($id);

        return view('adminPanel/checkers/checkerForm', ['mode' => 'edit', 'checker' => $checker, 'games' => Game::all(), 'user' => Auth::user()]);
    }

    public function createChecker(Request $request) {
        $this->validate($request, [
            "game" => "required",
            "name" => "required",
            "checkerSource" => "required",
        ]);

        $checker = new Checker();
        $checker->game = $request->input('game');
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

        return redirect('adminPanel/checkers');

    }

    public function editChecker(Request $request, $id) {

        $checker = Checker::findOrFail($id);

        if ($request->has('delete')) {

            Storage::disk('local')->delete('testers/' . $checker->id);
            Storage::disk('local')->delete('testers_bin/' . $checker->id);

            $checker->delete();

        } else if ($request->has('update')) {

            $checker->game = $request->input('game');
            $checker->name = $request->input('name');
            $checker->hasSeed = $request->has('hasSeed') ? 1 : 0;

            if ($request->has('checkerSource')) {
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

        return redirect('adminPanel/checkers');
    }
}
