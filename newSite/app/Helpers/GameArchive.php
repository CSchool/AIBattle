<?php

namespace AIBattle\Helpers;


use AIBattle\Attachment;
use AIBattle\Checker;
use AIBattle\Game;
use Chumper\Zipper\Zipper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GameArchive {

    /**
     * @param string $name
     * @param Collection $elements
     * @param integer $gameId
     * @param Command|null $command
     */
    private static function archiveMakeSection($name, $elements, $gameId, Command $command = null) {

        if ($elements->count() > 0) {

            if ($command)
                $command->comment(ucfirst($name) . " count - " . $elements->count());

            Storage::disk('local')->makeDirectory('games/' . $gameId . '/' . $name);

            $json = $elements->toJson(JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            Storage::disk('local')->put('games/' . $gameId . '/' . $name .'/' . $name . '.json', $json);

            if ($command)
                $command->line($json);

            foreach ($elements as $element) {
                $elementName = Schema::hasColumn($element->getTable(), 'originalName') ? $element->originalName : $element->id;
                Storage::disk('local')->copy( $name . '/' . $element->id, 'games/' . $gameId . '/' . $name . '/' . $elementName);
            }
        }
    }

    /**
     * Create game zip archive
     * @param Game $game
     * @param Command $command
     */
    public static function createArchive(Game $game, Command $command =  null) {
        if (class_exists('ZipArchive')) {
            // get information about game
            Storage::disk('local')->makeDirectory('games/' . $game->id);

            $gameJson = $game->toJson(JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            Storage::disk('local')->put('games/' . $game->id . '/description.json', $gameJson);

            if ($command)
                $command->line($gameJson);

            if ($game->hasVisualizer)
                Storage::disk('local')->copy('visualizers/' . $game->id, 'games/' . $game->id . '/visualizer.js');

            // get information about attachments
            GameArchive::archiveMakeSection('attachments', $game->attachments, $game->id, $command);

            // get information about checkers
            GameArchive::archiveMakeSection('testers', $game->checkers, $game->id, $command);

            // make zip file!
            Storage::disk('local')->delete('games/' . $game->id . '.zip');

            $archive = new Zipper();
            $archive->make(base_path() . '/storage/app/games/' . $game->id . '.zip')->add(base_path() . '/storage/app/games/' . $game->id)->close();

            File::deleteDirectory(base_path() . '/storage/app/games/' . $game->id);
        }
    }

    /**
     * Load game data (description, visualiser & attachments) from zip archive
     * @param string $file
     * @param Command $command
     */
    public static function loadArchive($file, Command $command = null) {
        if (class_exists('ZipArchive')) {
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


            // attachments

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

            // testers

            if (Storage::disk('local')->has('games/archive/tmp/testers/testers.json')) {
                $jsonTesters = Storage::disk('local')->get('games/archive/tmp/testers/testers.json');

                $testers = json_decode($jsonTesters, true);

                foreach ($testers as $tester) {
                    $newTester = new Checker();

                    $newTester->name = $tester['name'];
                    $newTester->hasSeed = $tester['hasSeed'];
                    $newTester->game_id = $game->id;

                    $newTester->save();

                    Storage::disk('local')->move('games/archive/tmp/testers/' . $tester['id'], 'testers/' . $newTester->id);

                    $process= new Process('/bin/bash gccChecker.sh ' . $newTester->id, base_path() . '/storage/app/compilers/' , ['PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games']);
                    $process->run();

                    if (!$process->isSuccessful())
                        throw new ProcessFailedException($process);
                }

            }

            GameArchive::createArchive($game, $command);

            File::deleteDirectory(base_path() . '/storage/app/games/archive/tmp');
        }
    }
}

