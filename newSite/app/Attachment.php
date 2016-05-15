<?php

namespace AIBattle;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    public function game() {
        return $this->belongsTo('AIBattle\Game');
    }
}
