<?php

namespace AIBattle;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function tournament() {
        return $this->belongsTo('AIBattle\Tournament');
    }

    public function checker() {
        return $this->belongsTo('AIBattle\Checker');
    }
    
    
}
