<?php

namespace AIBattle;

use Illuminate\Database\Eloquent\Model;

class Duel extends Model
{
    //

    public function strategy() {
        return $this->belongsTo('AIBattle\Strategy');
    }

}
