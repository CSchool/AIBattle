<?php

namespace AIBattle\Http\Controllers\AdminPanel;

use AIBattle\Attachment;
use AIBattle\Game;
use AIBattle\Helpers\GameArchive;
use Illuminate\Http\Request;

use AIBattle\Http\Requests;
use AIBattle\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;

class AttachmentsController extends Controller
{
    public function showAttachments($id) {
        $game = Game::findOrFail($id);

        return view('adminPanel/games/attachments/attachments', ['attachments' => $game->attachments()->count(), 'game' => $game]);
    }

    public function attachmentsTable($id) {
        $game = Game::findOrFail($id);
        $attachments = $game->attachments()->select(['id', 'originalName']);

        return Datatables::of($attachments)
                ->editColumn("originalName", function($attachment) use(&$id) {
                    return '<a href="' . url('/adminPanel/games', [$id, 'attachments', $attachment->id]) . '" role="button">' . $attachment->originalName . '</a>';
                })
                ->make(true);
    }

    public function showCreateAttachmentForm($id) {
        $game = Game::findOrFail($id);
        
        return view('adminPanel/games/attachments/attachmentForm', [
            'mode' => 'create',
            'attachmentsCount' => $game->attachments->count() + 1,
            'gameName' => $game->name,
            'gameId' => $game->id,
            'game' => $game,
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
                'game' => $game,
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

                $this->validate($request, [
                    'description' => 'required|max:128',
                ]);

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
