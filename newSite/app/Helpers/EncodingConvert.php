<?php

namespace AIBattle\Helpers;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class EncodingConvert {

    public static function convertToUTF8($path, $id) {
        if (Storage::disk('local')->has($path . '/' . $id)) {
            $utf8Process = new Process(
                '/bin/bash utf8.sh ../' . $path . '/' . $id,
                base_path() . '/storage/app/scripts/' ,
                ['PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games']
            );

            $utf8Process->run();

            if (!$utf8Process->isSuccessful())
                Log::error('Error while getting encoding: ' . $utf8Process->getErrorOutput());
        }
    }

    public static function getFileEncoding($file) {
        $process = new Process('/bin/bash scripts/utf8.sh ' . $file , base_path() . '/storage/app/');
        $process->run();

        if ($process->isSuccessful()) {
            $encoding = $process->getOutput();
            
            if (str_contains($encoding, 'iso-8859-1'))
                return "cp1251";
            else if (str_contains($encoding, 'utf'))
                return "UTF-8";
            else
                return 'auto';
        }
            
        else
            Log::error('Error while getting encoding: ' . $process->getErrorOutput());
    }
}