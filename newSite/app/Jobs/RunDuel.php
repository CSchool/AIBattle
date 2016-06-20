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

    protected $strategy1;
    protected $strategy2;
    protected $duel;
    protected $round;
    protected $checker = -1;
    protected $seed = 0;
    protected $hasSeed = false;

    /**
     * Create a new job instance.
     *
     * @param Strategy $strategy1
     * @param Strategy $strategy2
     * @param Duel $duel
     * @param int $roundId
     */
    public function __construct($strategy1, $strategy2, $duel, $roundId = -1)
    {
        $this->strategy1 = $strategy1;
        $this->strategy2 = $strategy2;
        $this->duel = $duel;
        $this->round = $roundId;

        // get data of checker and seed

        if ($roundId != -1) {

            $round = Round::findOrFail($roundId);
            $this->checker = $round->checker->id;
            $this->seed = $round->seed;

        } else {
            $this->seed = date("Y");
        }

        if ($this->round == -1 || $this->checker == -1) {
            $this->checker = $this->strategy1->tournament->defaultChecker;
        }

        // get hasSeed
        $this->hasSeed = Checker::findOrFail($this->checker)->hasSeed;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rand = strval(rand());

        $taskArray = [
            'before' => [
                'cmd' => "mkdir _chroot/$rand \n cp testers_bin/$this->checker _chroot/$rand/tester \n cp executions_bin/" . $this->strategy1->id . " _chroot/$rand/1 \n cp executions_bin/" . $this->strategy2->id . " _chroot/$rand/2 \n",
                'output' => '',
                'error' => ''
            ],

            'action' => [
                'cmd' => "fakechroot fakeroot chroot _chroot/$rand ./tester 1 2",
                'output' => '',
                'error' => ''
            ],
            'after' => [
                'cmd' => "rm -rf _chroot/$rand",
                'output' => '',
                'error' => ''
            ],

        ];

        if ($this->hasSeed) {
            $taskArray["action"]["cmd"] .= " " . $this->seed;
        }

        foreach ($taskArray as $key => $task) {
            $process = DuelProcess::getProcess($task['cmd']);
            $process->run();

            if ($process->isSuccessful()) {
                $taskArray[$key]['output'] = $process->getOutput();
            } else {
                $taskArray[$key]['error'] = $process->getErrorOutput();

                throw new ProcessFailedException($process);
            }
        }

        $output = explode(PHP_EOL, $taskArray['action']['output']);
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

        if ($this->round != -1) {
            if ($curStat == "TIE" || $curStat == "IE") {
                $members = [$this->strategy1, $this->strategy2];

                foreach ($members as $strategy) {
                    $this->setPlayerScore($strategy, ($curStat == "TIE") ? 1 : 0);
                }
            }
            elseif ($curStat != "IL") {

                $strategy = null;

                if ($curStat == "WIN") {
                    if ($playerId == 1) {
                        $strategy = $this->strategy1;
                    } else {
                        $strategy = $this->strategy2;
                    }
                } else {
                    if ($playerId == 1) {
                        $strategy = $this->strategy2;
                    } else {
                        $strategy = $this->strategy1;
                    }
                }

                $this->setPlayerScore($strategy, 2);
            }
        }

        $result = "PLAYERS\n" . $this->strategy1->user->username . "\n" . $this->strategy2->user->username . "\n";

        Storage::disk('local')->put('logs/' . $this->duel->id, $result . "\n" . $taskArray['action']['output']);

        $this->duel->status = $testerStat;
        $this->duel->save();
    }

    private function setPlayerScore($strategy, $value) {
        $score = new Score();

        $score->round_id = $this->round;
        $score->strategy_id = $strategy->id;
        $score->score = $value;

        $score->save();

    }
}
