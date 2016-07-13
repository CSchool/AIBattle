<?php

namespace AIBattle\Helpers;

use AIBattle\Checker;
use AIBattle\Duel;
use AIBattle\Round;
use AIBattle\Strategy;

class DuelPair {
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
        $this->roundId = $roundId;

        // get data of checker and seed

        if ($roundId != -1) {

            $this->round = Round::findOrFail($roundId);
            $this->checker = $this->round->checker->id;
            $this->seed = $this->round->seed;

        } else {
            $this->seed = date("Y");
        }

        if ($this->roundId == -1 || $this->checker == -1) {
            $this->checker = $this->strategy1->tournament->defaultChecker;
        }

        // get hasSeed
        $this->hasSeed = Checker::findOrFail($this->checker)->hasSeed;
    }

    /**
     * @return Strategy
     */
    public function getStrategy1()
    {
        return $this->strategy1;
    }

    /**
     * @return Strategy
     */
    public function getStrategy2()
    {
        return $this->strategy2;
    }

    /**
     * @return Duel
     */
    public function getDuel()
    {
        return $this->duel;
    }

    /**
     * @return Round
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @return int
     */
    public function getRoundId()
    {
        return $this->roundId;
    }
    
    /**
     * @return int
     */
    public function getChecker()
    {
        return $this->checker;
    }

    /**
     * @return int
     */
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * @return boolean
     */
    public function hasSeed()
    {
        return $this->hasSeed;
    }
    
    
}