<?php

namespace AIBattle;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    //
    public function getGame() {
        return Game::findOrFail($this->game);
    }

    public function getChecker() {
        return Checker::findOrFail($this->defaultChecker);
    }

}
