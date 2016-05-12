<?php

namespace AIBattle;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use AIBattle\Game;


class Checker extends Model
{

    public function getCheckerData() {
        if (Storage::disk('local')->has('testers/' . $this->id))
            return Storage::disk('local')->get('testers/' . $this->id);
        else
            return "";
    }
}
