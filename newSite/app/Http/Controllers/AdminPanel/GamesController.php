<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Game;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GamesController extends Controller
{
    public function showGames() {
        return view('adminPanel/games/games', ['games' => Game::orderBy('id')->simplePaginate(10)]);
    }

    public function showCreateGameForm() {
        return view('adminPanel/games/gameForm', ['mode' => "create", 'gameCount' => Game::all()->count() + 1]);
    }

    public function showEditGameForm($id) {
        $game = Game::findOrFail($id);
        return view('adminPanel/games/gameForm', ['mode' => "edit", 'game' => $game]);
    }

    public function showGameById($id) {
        $game = Game::findOrFail($id);
        return view('adminPanel/games/showGame', ['game' => $game, 'visualizerData' => $game->getVisualizerData()]);
    }

    public function createGame(Request $request) {

        $this->validate($request, [
            'name' => 'required|max:32',
            'description' => 'required',
            'timeLimit' => 'required|min:50|numeric',
            'memoryLimit' => 'required|min:10|numeric',
        ]);

        $game = new Game();

        $game->name = $request->input('name');
        $game->description = $request->input('description');
        $game->timeLimit = $request->input('timeLimit');
        $game->memoryLimit = $request->input('memoryLimit');

        $game->save();

        if ($request->hasFile('visualizer')) {
            $game->hasVisualizer = true;
            $game->save();
            $request->file('visualizer')->move(base_path() . '/storage/app/visualizers/', $game->id);
        } else {
            $game->save();
        }

        return redirect('adminPanel/games');
    }

    public function editGame(Request $request, $id) {

        if ($request->has('delete')) {
            $game = Game::findOrFail($id);

            Storage::disk('local')->delete('visualizers/' . $game->id);
            $game->delete();

            return redirect('adminPanel/games');

        } elseif ($request->has('update')) {
            
            $this->validate($request, [
                'name' => 'required|max:32',
                'description' => 'required',
                'timeLimit' => 'required|min:50|numeric',
                'memoryLimit' => 'required|min:10|numeric',
            ]);

            $game = Game::findOrFail($id );

            $game->name = $request->input('name');
            $game->description = $request->input('description');
            $game->timeLimit = $request->input('timeLimit');
            $game->memoryLimit = $request->input('memoryLimit');

            if ($request->has('deleteVisualizer')) {
                // remove
                Storage::disk('local')->delete('visualizers/' . $game->id);
                $game->hasVisualizer = false;
            } else {
                // replace
                if ($request->hasFile('visualizer')) {
                    $request->file('visualizer')->move(base_path() . '/storage/app/visualizers/', $game->id);
                    $game->hasVisualizer = true;
                }
            }

            $game->save();

            return redirect('adminPanel/games');

        }
        else
            abort(404);
    }

}
