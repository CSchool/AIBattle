<?php

namespace AIBattle;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    //

    public function game() {
        return $this->belongsTo('AIBattle\Game');
    }

    public function checker() {
        return $this->belongsTo('AIBattle\Checker', 'defaultChecker');
    }

    public function strategies() {
        return $this->hasMany('AIBattle\Strategy');
    }

    public function getGame() {
        return Game::findOrFail($this->game_id);
    }

    public function getChecker() {
        return Checker::findOrFail($this->defaultChecker);
    }

}
