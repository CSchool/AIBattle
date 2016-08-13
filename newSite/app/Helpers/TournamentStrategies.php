<?php

namespace AIBattle\Helpers;

use AIBattle\Tournament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\Datatables\Datatables;

class TournamentStrategies {

    private static function popoverTag($text, $isError = false) {
        $sign = $isError ? 'glyphicon-fire' : 'glyphicon-info-sign';
        return "<a data-toggle=\"popover\" data-trigger=\"hover\" data-content=\"$text\"><span class=\"glyphicon $sign\"></span></a>";
    }

    public static function getStrategiesUsersByTournament($tournamentId) {
        $users = Tournament::find($tournamentId)
            ->strategies()
            ->distinct()
            ->join('users', 'users.id', '=', 'user_id')
            ->select('users.username as username')
            ->get();

        return $users->toJson();
    }

    public static function getDatatables($query, $isAdmin = false) {

        $data = Datatables::of($query)
            ->removeColumn('description')
            ->removeColumn('tournament_id')
            ->editColumn('name', function ($strategy) {
                if ($strategy->user_id == Auth::user()->id) {
                    $additionalText = '';

                    if ($strategy->status == 'ERR')
                        $additionalText = TournamentStrategies::popoverTag(trans('tournaments/strategies.showStrategiesFailedCompilation'), true);
                    elseif (!empty($strategy->description))
                        $additionalText = TournamentStrategies::popoverTag(strip_tags($strategy->description));

                    return '<a href=' . url('tournaments/' . $strategy->tournament_id . '/strategies', [$strategy->id]) . '>' . $strategy->name . '</a> ' . $additionalText;
                } else {
                    return '<a href=' . url('tournaments/' . $strategy->tournament_id . '/strategies', [$strategy->id]) . '>' . trans('shared.strategy') . $strategy->id . '</a> ';
                }

            })
            ->addColumn('setActive', function($strategy) {
                if ($strategy->status == 'OK') {
                    return '<a href="' . url('tournaments/' . $strategy->tournament->id . '/strategies/' . $strategy->id . '/setActive') . '" class="btn-xs btn-warning"><i class="glyphicon glyphicon-ok"></i> ' . trans('tournaments/strategies.strategyProfileMakeActualStrategy') . '</a>';
                } else {
                    return '';
                }
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
            });

        if (!$isAdmin) {
            $data->addColumn('users.username', function($strategy) {
                return $strategy->username;
            });
        }

        return $data->make(true);
    }
}