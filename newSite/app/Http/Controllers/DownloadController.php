<?php

namespace AIBattle\Http\Controllers;

use AIBattle\Attachment;
use AIBattle\Game;
use AIBattle\Tournament;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    //
    public function downloadAttachment($game, $attachment) {
        $attachment = Attachment::findOrFail($attachment);

        if ($attachment->game_id == $game)
            return response()->download(base_path() . '/storage/app/attachments/' . $attachment->id, $attachment->originalName);
        else
            abort(404);
    }

    public function downloadAttachmentByTournament($tournament, $attachmentId) {
        if (Tournament::findOrFail($tournament)->game->attachments()->where('id', $attachmentId)->first()) {
            $attachment = Attachment::find($attachmentId);
            return response()->download(base_path() . '/storage/app/attachments/' . $attachment->id, $attachment->originalName);
        }
        else
            abort(404);
    }
    
    public function downloadGameArchive($gameId) {
        $game = Game::findOrFail($gameId);

        if (Storage::disk('local')->has('games/' . $game->id . '.zip'))
            return response()->download(base_path() . '/storage/app/games/' . $game->id . '.zip', $game->name . '.zip');
        else
            abort(404);
    }
}
