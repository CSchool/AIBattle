<?php

namespace AIBattle\Jobs;

use AIBattle\Checker;
use AIBattle\Duel;
use AIBattle\Helpers\DuelProcess;
use AIBattle\Jobs\Job;
use AIBattle\Round;
use AIBattle\Score;
use AIBattle\Strategy;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RunDuel extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $duels;

    /**
     * Create a new job instance.
     *
     * @param array $duels
     */
    public function __construct($duels)
    {
        $this->duels = $duels;
    }

    private function getPath($path) {
        return base_path() . '/storage/app/' . $path;
    }

    private function setPlayerScore($duelPair, $strategy, $value) {
        $score = new Score();

        $score->round_id = $duelPair->getRoundId();
        $score->strategy_id = $strategy->id;
        $score->score = $value;

        $score->save();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->duels as $duelPair) {
            $rand = strval(rand());

            $taskArray = [
                'before' => [
                    'cmd' => "mkdir _chroot/$rand \n cp testers_bin/" . $duelPair->getChecker() . " _chroot/$rand/tester \n cp executions_bin/" . $duelPair->getStrategy1()->id . " _chroot/$rand/1 \n cp executions_bin/" . $duelPair->getStrategy2()->id . " _chroot/$rand/2 \n",
                    //'cmd' => "mkdir " . $this->getPath("_chroot/$rand") . "\n cp " . $this->getPath("testers_bin/" . $duelPair->getChecker()) . " " . $this->getPath("_chroot/$rand/tester") . " \n cp " . $this->getPath("executions_bin/" . $duelPair->getStrategy1()->id) . " " . $this->getPath("_chroot/$rand/1") . " \n cp " . $this->getPath("executions_bin/" . $duelPair->getStrategy2()->id) . " " . $this->getPath("_chroot/$rand/2") . " \n",
                    'output' => '',
                    'error' => ''
                ],

                'action' => [
                    'cmd' => "fakechroot fakeroot chroot _chroot/$rand ./tester 1 2",
                    'output' => '',
                    'error' => ''
                ],
                'after' => [
                    'cmd' => "rm -rf " . $this->getPath("_chroot/$rand"),
                    'output' => '',
                    'error' => ''
                ],

            ];

            if ($duelPair->hasSeed()) {
                $taskArray["action"]["cmd"] .= " " . $duelPair->getSeed();
            }

            foreach ($taskArray as $key => $task) {

               //exec($task['cmd'],  $taskArray[$key]['output']);

                $process = DuelProcess::getProcess($task['cmd']);
                $process->run();

                if ($process->isSuccessful()) {
                    $taskArray[$key]['output'] = $process->getOutput();
                } else {
                    $taskArray[$key]['error'] = $process->getErrorOutput();

                    $this->duel->status = 'ERR';
                    $this->duel->save();

                    Log::error("Duel " . $this->duel->id . " has crashed! Log: " . $taskArray[$key]['error']);

                    throw new ProcessFailedException($process);
                }

            }

            $output = explode(PHP_EOL, $taskArray['action']['output']);
            //$output = $taskArray['action']['output'];
            $source = current($output);

            $playerId = 0;
            $curStat = "";

            $stat = array("OK", "FIELD", "WIN", "TIE", "IM", "TL", "RE", "ML", "IE", "SV", "INPUT");

            $testerStat = 'IL';
            $curStat = 'IL';

            while (current($output) !== false)
            {
                foreach ($stat as $part)
                {
                    if (strpos($source, $part) === 0)
                    {
                        if ($part == "OK")
                            while (current($output) !== false && current($output) != "END_OF_OUTPUT")
                                $source = next($output);
                        else if ($part == "IM")
                        {
                            $testerStat = $source;
                            $curStat = $part;
                            $partArr = explode(" ", $source);
                            $playerId = intval($partArr[1]);
                            while (current($output) !== false && current($output) != "END_OF_OUTPUT")
                                $source = next($output);
                        }
                        else if ($part == "FIELD")
                            while (current($output) !== false && current($output) != "END_OF_FIELD")
                                $source = next($output);
                        else if ($part == "INPUT")
                            while (current($output) !== false && current($output) != "END_OF_INPUT")
                                $source = next($output);
                        else if ($part == "TIE" || $part == "IE")
                        {
                            $curStat = $part;
                            $testerStat = $source;
                        }
                        else
                        {
                            $testerStat = $source;
                            $curStat = $part;
                            $partArr = explode(" ", $source);
                            $playerId = intval($partArr[1]);
                        }
                        break;
                    }
                }
                $source = next($output);
            }

            if ($duelPair->getRoundId() != -1) {
                if ($curStat == "TIE" || $curStat == "IE") {
                    $members = [$duelPair->getStrategy1(), $duelPair->getStrategy2()];

                    foreach ($members as $strategy) {
                        $this->setPlayerScore($duelPair, $strategy, ($curStat == "TIE") ? 1 : 0);
                    }
                }
                elseif ($curStat != "IL") {

                    $strategy = null;

                    if ($curStat == "WIN") {
                        if ($playerId == 1) {
                            $strategy = $duelPair->getStrategy1();
                        } else {
                            $strategy = $duelPair->getStrategy2();
                        }
                    } else {
                        if ($playerId == 1) {
                            $strategy = $duelPair->getStrategy2();
                        } else {
                            $strategy = $duelPair->getStrategy1();
                        }
                    }

                    $this->setPlayerScore($duelPair, $strategy, 2);
                }
            }

            $result = "PLAYERS\n" . $duelPair->getStrategy1()->user->username . "\n" . $duelPair->getStrategy2()->user->username . "\n";
            Storage::disk('local')->put('logs/' . $duelPair->getDuel()->id, $result . "\n" . $taskArray['action']['output']);

            $duelPair->getDuel()->status = $testerStat;
            $duelPair->getDuel()->save();
        }
    }
}
