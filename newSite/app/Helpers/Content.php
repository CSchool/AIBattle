<?php

namespace AIBattle\Helpers;

use AIBattle\Duel;
use Illuminate\Support\Facades\Storage;

class Content {

    /**
     * @param Duel $duel
     * @return string
     */
    public static function getVisualization($duelId) {

        if (Storage::disk('local')->exists('logs/' . $duelId)) {
            $file = Storage::disk('local')->get('logs/' . $duelId);

            $parsedFile = explode(PHP_EOL, $file);

            $log = '';
            $s = current($parsedFile);

            while ($s !== false)
            {
                if (strpos($s, "OK") === 0 || strpos($s, "IM") === 0)
                {
                    while ($s !== false && $s != "END_OF_OUTPUT")
                        $s = next($parsedFile);
                }
                else if ($s == "FIELD")
                {
                    $s = next($parsedFile);
                    while ($s !== false && $s != "END_OF_FIELD")
                    {
                        $log .= trim($s) . "\n";
                        $s = next($parsedFile);
                    }
                }
                else if (strpos($s, "INPUT") === 0)
                {
                    while ($s !== false && $s != "END_OF_INPUT")
                        $s = next($parsedFile);
                }
                $s = next($parsedFile);
            }

            return $log;
        } else
            return '';

    }
}