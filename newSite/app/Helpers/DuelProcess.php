<?php

namespace AIBattle\Helpers;

use Symfony\Component\Process\Process;

class DuelProcess {
    /**
     * @param $cmd
     * @return Process
     */
    public static function getProcess($cmd) {
        return new Process(
            $cmd,
            base_path() . '/storage/app/' ,
            ['PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games']
        );
    }
}