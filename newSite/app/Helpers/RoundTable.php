<?php

namespace AIBattle\Helpers;

use AIBattle\Duel;
use AIBattle\Round;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoundTable
{
    private $scores;
    private $round;

    public function __construct($roundId)
    {
        $this->round = Round::findOrFail($roundId);
        $this->scores = new Collection(
            DB::select(
                'SELECT SUM(score) AS score, users.username AS strategy, users.id as userId, strategy_id FROM scores INNER JOIN strategies ON strategies.id = strategy_id INNER JOIN users ON strategies.user_id = users.id WHERE round_id = ' . $roundId . ' GROUP BY strategy'
            )
        );
    }

    public function getScores()
    {
        return $this->scores;
    }

    public function getPlayers() {
        return $this->scores->map(function ($entry) {
            return [
                "username" => $entry->strategy,
                "strategy" => $entry->strategy_id,
            ];
        });
    }

    public function getRoundTable() {
        $players = $this->getPlayers();
        $result = new Collection();

        foreach ($this->scores as $entry) {

            $row = [];

            foreach ($players as $player) {
                if ($player["strategy"] != $entry->strategy_id) {

                    Log::info($player["strategy"] . " vs " . $entry->strategy_id);

                    $r1 = Duel::where('round', $this->round->id)
                        ->where('strategy1', $entry->strategy_id)
                        ->where('strategy2', $player["strategy"])
                        ->select('id', 'status')->firstOrFail();

                    $r2 = Duel::where('round', $this->round->id)
                        ->where('strategy2', $entry->strategy_id)
                        ->where('strategy1', $player["strategy"])
                        ->select('id', 'status')->firstOrFail();

                      array_push($row, [
                          $this->getRoundTableCortege($r1->id, $this->getScore($r1->status, true), $r1->status),
                          $this->getRoundTableCortege($r2->id, $this->getScore($r2->status, false), $r2->status)
                      ]);
                } else {
                    array_push($row, []);
                }
            }

            $result->put($entry->strategy_id, $row);
        }

        return $result;
    }

    private function getScore($status, $first) {
        $status = trim($status);
        $result = 0;

        $victory = [
            "1" => ["WIN 1", "IM 2", "RE 2", "TL 2", "ML 2"],
            "2" => ["WIN 2", "IM 1", "RE 1", "TL 1", "ML 1"],
        ];

        if ($status == "TIE") {
            return 1;
        } else {
            return in_array($status, $victory[$first == true ? "1" : "2"]) == true ? 2 : 0;
        }
    }

    private function getRoundTableCortege($id, $score, $status) {
        return [
            "id" => $id,
            "score" => $score,
            "status" => $status
        ];
    }
}