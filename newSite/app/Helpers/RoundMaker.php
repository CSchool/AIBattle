<?php

namespace AIBattle\Helpers;


use AIBattle\Duel;
use AIBattle\Jobs\RunDuel;
use AIBattle\Round;
use AIBattle\Strategy;
use AIBattle\Tournament;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class RoundMaker  {
    public static function makeRound($data, $tournamentId) {

        $tournament = Tournament::findOrFail($tournamentId);

        $round = new Round();

        $round->name = $data["name"];
        $round->tournament_id = $tournamentId;
        $round->game_id = $tournament->game->id;
        $round->checker_id = $data["checker"];

        $round->date = Carbon::now()->toDateTimeString();

        if (array_key_exists("seed", $data)) {
            $round->seed = $data["seed"];
        } else {
            $round->seed = "";
        }

        if (array_key_exists("previousRound", $data)) {
            $round->previousRound = $data["previousRound"];
        }

        $round->save();

        // make duels

        $duels = [];

        foreach ($data["players"] as $player1) {
            foreach($data["players"] as $player2) {
                if ($player1["strategyId"] != $player2["strategyId"]) {
                    // this is not the same player - can create duel

                    $duel = new Duel();

                    $duel->strategy1 = $player1["strategyId"];
                    $duel->strategy2 = $player2["strategyId"];
                    $duel->round = $round->id;
                    $duel->status = 'W';

                    $duel->save();

                    $job = new DuelPair(
                        Strategy::findOrFail($player1["strategyId"]),
                        Strategy::findOrFail($player2["strategyId"]),
                        $duel,
                        $round->id);

                    array_push($duels, $job);
                }
            }
        }

        return $duels;
        
    }
}