<?php

class Logger extends SuperConfig
{
    public function __construct() {
        parent::__construct();
    }

    public static function writeExceptionLog($type, $message) {
        $exceptionTypes = [
            1 => "NOTICE",
            2 => "WARNING",
            3 => "FATAL ERROR"
        ];
        $dateTime = date('m/d/Y h:i:s', time());
        $infoString = "[{$exceptionTypes[$type]}] | {$dateTime}: {$message}";

        $fileHandle = fopen(SuperConfig::getLogDir()."EXCEPTION_log.txt", "w");
        fwrite($fileHandle, $infoString);
        fclose($fileHandle);
    }
}