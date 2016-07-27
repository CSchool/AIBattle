<?php

namespace AIBattle\Http\Controllers;

use AIBattle\Attachment;
use AIBattle\Duel;
use AIBattle\Game;
use AIBattle\Round;
use AIBattle\Tournament;
use AIBattle\User;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    //
    public function downloadAttachment($game, $attachment) {
        $attachment = Attachment::findOrFail($attachment);

        if ($attachment->game_id == $game)
            return response()->download(base_path() . '/storage/app/attachments/' . $attachment->id, $attachment->originalName);
        else
            abort(404, 'File not found!');
    }

    public function downloadAttachmentByTournament($tournament, $attachmentId) {
        if (Tournament::findOrFail($tournament)->game->attachments()->where('id', $attachmentId)->first()) {
            $attachment = Attachment::find($attachmentId);
            return response()->download(base_path() . '/storage/app/attachments/' . $attachment->id, $attachment->originalName);
        }
        else
            abort(404, 'File not found!');
    }
    
    public function downloadGameArchive($gameId) {
        $game = Game::findOrFail($gameId);

        if (Storage::disk('local')->has('games/' . $game->id . '.zip'))
            return response()->download(base_path() . '/storage/app/games/' . $game->id . '.zip', $game->name . '.zip');
        else
            abort(404, 'File not found!');
    }

    public function downloadLog($tournamentId, $duelId) {
        // check permissions

        $duel = Duel::findOrFail($duelId);
        $tournament = Tournament::findOrFail($tournamentId);

        $round = null;

        if ($duel->round != -1) {
            $round = Round::findOrFail($duel->round);
        }

        $userId = Auth::user()->id;

        $duelUsers = Duel::join('strategies as s1', 'duels.strategy1', '=', 's1.id')
                                ->join('strategies as s2', 'duels.strategy2', '=', 's2.id')
                                ->join('users as usr1', 's1.user_id', '=', 'usr1.id')
                                ->join('users as usr2', 's2.user_id', '=', 'usr2.id')
                                ->where('duels.id', $duelId)
                                ->where(function ($query) use(&$userId) {
                                    $query->where('usr1.id', $userId)->orWhere('usr2.id', $userId);
                                })
                                ->count();


        if (User::isAdmin() || $duelUsers > 0 || ($round != null && $round->visible == 1)) {
            if (Storage::disk('local')->has('logs/' . $duelId)) {
                return response()->download(base_path() . '/storage/app/logs/' . $duelId);
            } else {
                abort(404, 'Log not found!');
            }
        } else {
            abort(403);
        }
    }
}
