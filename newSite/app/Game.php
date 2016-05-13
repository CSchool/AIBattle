<?php

namespace AIBattle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use AIBattle\Checker;

class Game extends Model
{
    public function checkers() {
        return $this->hasMany('AIBattle\Checker');
    }

    public function getVisualizerData() {
        if ($this->hasVisualizer && Storage::disk('local')->has('visualizers/' . $this->id))
            return Storage::disk('local')->get('visualizers/' . $this->id);
        else
            return "";
    }
}
