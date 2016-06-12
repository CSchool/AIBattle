<?php

namespace AIBattle;

use AIBattle\Helpers\EncodingConvert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use AIBattle\Checker;

class Game extends Model
{
    public function checkers() {
        return $this->hasMany('AIBattle\Checker');
    }

    public function attachments() {
        return $this->hasMany('AIBattle\Attachment');
    }

    public function getVisualizerData() {
        if ($this->hasVisualizer && Storage::disk('local')->has('visualizers/' . $this->id))
            return EncodingConvert::getConvertedContent('visualizers/' . $this->id);
        else
            return "";
    }
}
