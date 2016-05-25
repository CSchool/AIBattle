<?php

namespace AIBattle;

use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    //

    /**
     * Get language by checker name
     * @param string $checker
     * @return mixed|string
     */
    public static function getLanguageByChecker($checker) {
        $languages = ['gcc' => 'C++', 'fpc' => 'Pascal'];

        if (!array_key_exists($checker, $languages))
            return '';
        else
            return $languages[$checker];
    }

    /**
     * Get class name for prism.js by checker
     * @param $checker
     * @return mixed|string
     */
    public static function getLanguagePrismClass($checker) {
        $prismClass = ['gcc' => 'language-cpp', 'fpc' => 'language-pascal'];

        if (!array_key_exists($checker, $prismClass))
            return 'language-clike';
        else
            return $prismClass[$checker];
    }

    public function setActive($userId, $tournamentId) {
        Strategy::where('user_id', $userId)
            ->where('tournament_id', $tournamentId)
            ->where('status', 'ACT')
            ->update(['status' => 'OK']);

        $this->status = 'ACT';
    }

    public function tournament() {
        return $this->belongsTo('AIBattle\Tournament');
    }

    public function game() {
        return $this->belongsTo('AIBattle\Game');
    }

    public function user() {
        return $this->belongsTo('AIBattle\User');
    }
}
