<?php

namespace AIBattle\Helpers;


use AIBattle\Attachment;
use AIBattle\Game;
use Chumper\Zipper\Zipper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GameArchive {


    /**
     * Create game zip archive
     * @param Game $game
     * @param Command $command
     */
    public static function createArchive(Game $game, Command $command =  null) {
        // get information about game
        Storage::disk('local')->makeDirectory('games/' . $game->id);

        $gameJson = $game->toJson(JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        Storage::disk('local')->put('games/' . $game->id . '/description.json', $gameJson);

        if ($command)
            $command->line($gameJson);

        if ($game->hasVisualizer)
            Storage::disk('local')->copy('visualizers/' . $game->id, 'games/' . $game->id . '/visualizer.js');

        // get information about attachments
        $attachments = $game->attachments;

        if ($attachments->count() > 0) {

            if ($command)
                $command->comment("Attachemnts count - " . $attachments->count());

            Storage::disk('local')->makeDirectory('games/' . $game->id . '/attachments');

            $attachmentsJson = $attachments->toJson(JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            Storage::disk('local')->put('games/' . $game->id . '/attachments/attachments.json', $attachmentsJson);

            if ($command)
                $command->line($attachmentsJson);

            // copy files
            foreach ($attachments as $attachment) {
                Storage::disk('local')->copy('attachments/' . $attachment->id, 'games/' . $game->id . '/attachments/' . $attachment->originalName);
            }
        }

        // make zip file!
        Storage::disk('local')->delete('games/' . $game->id . '.zip');

        $archive = new Zipper();
        $archive->make(base_path() . '/storage/app/games/' . $game->id . '.zip')->add(base_path() . '/storage/app/games/' . $game->id)->close();

        File::deleteDirectory(base_path() . '/storage/app/games/' . $game->id);
    }

    /**
     * Load game data (description, visualiser & attachments) from zip archive
     * @param string $file
     * @param Command $command
     */
    public static function loadArchive($file, Command $command = null) {
        Storage::disk('local')->makeDirectory('games/archive/tmp');

        $archive = new Zipper();
        $archive->make($file)->extractTo(base_path() . '/storage/app/games/archive/tmp');

        $jsonDescription = Storage::disk('local')->get('games/archive/tmp/description.json');
        $description = json_decode($jsonDescription, true);

        $game = new Game();

        $game->name = $description['name'];
        $game->description = $description['description'];
        $game->timeLimit = $description['timeLimit'];
        $game->memoryLimit = $description['memoryLimit'];
        $game->hasVisualizer = $description['hasVisualizer'];

        $game->save();

        if ($game->hasVisualizer)
            Storage::disk('local')->move('games/archive/tmp/visualizer.js', 'visualizers/' . $game->id);

        if (Storage::disk('local')->has('games/archive/tmp/attachments/attachments.json')) {

            $jsonAttachments = Storage::disk('local')->get('games/archive/tmp/attachments/attachments.json');

            $attachments = json_decode($jsonAttachments, true);

            foreach ($attachments as $attachment) {
                $newAttachment = new Attachment();

                $newAttachment->originalName = $attachment['originalName'];
                $newAttachment->description = $attachment['description'];
                $newAttachment->game_id = $game->id;

                $newAttachment->save();

                Storage::disk('local')->move('games/archive/tmp/attachments/' . $newAttachment->originalName, 'attachments/' . $newAttachment->id);
            }
        }

        GameArchive::createArchive($game, $command);

        File::deleteDirectory(base_path() . '/storage/app/games/archive/tmp');
    }
}

