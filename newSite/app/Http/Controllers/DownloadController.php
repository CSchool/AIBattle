<?php

namespace AIBattle\Http\Controllers;

use AIBattle\Attachment;
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
}
