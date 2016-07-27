<?php

namespace AIBattle\Helpers;

use AIBattle\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DuelsQuery {
    public static function data($roundId, $gameId, $userId, $tournamentId = -1) {

        $isAdmin = User::isAdmin();

        $data = DB::table('duels')
                    ->join('strategies as s1', 'duels.strategy1', '=', 's1.id')
                    ->join('strategies as s2', 'duels.strategy2', '=', 's2.id')
                    ->join('users as usr1', 's1.user_id', '=', 'usr1.id')
                    ->join('users as usr2', 's2.user_id', '=', 'usr2.id')
                    ->join('games', function($join) {
                        $join->on('games.id', '=', 's1.game_id')->on('games.id', '=', 's2.game_id');
                    })
                    ->where('duels.round', $roundId)
                    ->where('games.id', $gameId);



        if (!$isAdmin && $roundId != -1) {
            $data->join('rounds', 'rounds.id', '=', 'duels.round')->where('rounds.visible', 1);
        }

        if (!$isAdmin && $roundId == -1)
            $data->where(function ($query) use(&$userId) {
                $query->where('s1.user_id', $userId)->orWhere('s2.user_id', $userId);
            });

        if ($tournamentId != -1)
            $data->where(function ($query) use(&$tournamentId) {
                $query->where('s1.tournament_id', $tournamentId)->where('s2.tournament_id', $tournamentId);
            });

        $data->select('duels.id as id', 'usr1.username as user1', 'usr2.username as user2', 'duels.status', 'games.hasVisualizer');

        return $data;
    }
}