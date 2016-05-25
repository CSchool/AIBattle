<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Attachment;
use AIBattle\Game;
use AIBattle\Helpers\GameArchive;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AttachmentsController extends Controller
{
    public function showAttachments($id) {
        $game = Game::findOrFail($id);

        return view('adminPanel/games/attachments/attachments', ['attachments' => $game->attachments()->simplePaginate(10), 'game' => $game]);
    }

    public function showCreateAttachmentForm($id) {
        $game = Game::findOrFail($id);
        
        return view('adminPanel/games/attachments/attachmentForm', [
            'mode' => 'create',
            'attachmentsCount' => $game->attachments->count() + 1,
            'gameName' => $game->name,
            'gameId' => $game->id,
        ]);
    }

    public function showAttachmentById($id, $attachmentId) {
        $game = Game::findOrFail($id);
        $attachment = Attachment::findOrFail($attachmentId);

        if ($game->attachments()->where('id', $attachmentId)->first())
            return view('adminPanel/games/attachments/showAttachment', [
                'game' => $game,
                'attachment' => $attachment,
            ]);
        else
            abort(404);
    }

    public function showEditAttachmentForm($id, $attachmentId) {
        $game = Game::findOrFail($id);
        $attachment = Attachment::findOrFail($attachmentId);

        if ($game->attachments()->where('id', $attachmentId)->first())
            return view('adminPanel/games/attachments/attachmentForm', [
                'mode' => 'edit',
                'attachment' => $attachment,
                'gameName' => $game->name,
            ]);
        else
            abort(404);
    }

    public function createAttachment(Request $request, $id) {
        $game = Game::findOrFail($id);

        $this->validate($request, [
            'description' => 'required|max:128',
            'attachmentSource' => 'required',
        ]);

        $attachment = new Attachment();

        $attachment->originalName = $request->file('attachmentSource')->getClientOriginalName();
        $attachment->description = $request->input('description');
        $attachment->game_id = $id;

        $attachment->save();

        $request->file('attachmentSource')->move(base_path() . '/storage/app/attachments/', $attachment->id);

        GameArchive::createArchive($game);

        return redirect('adminPanel/games/' . $game->id . '/attachments');
    }

    public function editAttachment(Request $request, $id, $attachmentId) {
        $game = Game::findOrFail($id);
        $attachment = Attachment::findOrFail($attachmentId);

        if ($game->attachments()->where('id', $attachmentId)->first()) {

            if ($request->has('delete')) {
                Storage::disk('local')->delete('attachments/' . $attachment->id);
                $attachment->delete();
            } elseif ($request->has('update')) {

                $attachment->description = $request->input('description');
                $attachment->game_id = $id;

                if ($request->hasFile('attachmentSource')) {
                    $attachment->originalName = $request->file('attachmentSource')->getClientOriginalName();
                    $request->file('attachmentSource')->move(base_path() . '/storage/app/attachments/', $attachment->id);
                }

                $attachment->save();
            }

            GameArchive::createArchive($game);

            return redirect('adminPanel/games/' . $game->id . '/attachments');
        } else
            abort(404);
    }
}
