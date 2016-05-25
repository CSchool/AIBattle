<?php

namespace AIBattle\Helpers;

use Symfony\Component\Process\Process;

class CompilerProcess {
    /**
     * @param string $checker
     * @param string $id
     * @return Process
     */
    public static function getProcess($checker, $id) {
        return new Process(
            '/bin/bash ' . $checker . ' ' . $id,
            base_path() . '/storage/app/compilers/' ,
            ['PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games']
        );
    }
}