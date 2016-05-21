<?php

namespace AIBattle\Console\Commands;

use AIBattle\Helpers\GameArchive;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LoadGames extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load games and attachments from archives at storage/app/games/archive';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Remove only files (without directories and gitignore) in path
     * @param string $path
     */
    private function removeFiles($path) {
        $files = Storage::disk('local')->files($path);

        foreach ($files as $file) {
            if (!strstr($file, '.gitignore') && !File::isDirectory($file))
                Storage::disk('local')->delete($file);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // deleting files before copying !!!

        $this->removeFiles('attachments');
        $this->removeFiles('games');
        $this->removeFiles('visualizers');

        // load games

        $files = Storage::disk('local')->allFiles('games/archive');

        foreach ($files as $file) {

            $this->info('Get file: ' . $file);

            if (!strstr($file, '.gitignore'))
                GameArchive::loadArchive(base_path() . '/storage/app/' . $file, $this);

        }

        $this->info('All games and attachments was added!');

    }
}
