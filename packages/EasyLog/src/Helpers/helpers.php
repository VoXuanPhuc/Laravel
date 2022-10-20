<?php

use Encoda\EasyLog\EasyLogger;
use Encoda\EasyLog\Entities\EasyLogStatus;

if (! function_exists('activity')) {
    function activity(string $logName = null): EasyLogger
    {
        $defaultLogName = config('easylog.default_log_name');

        $logStatus = app(EasyLogStatus::class);

        return app(EasyLogger::class)
            ->useLog($logName ?? $defaultLogName)
            ->setLogStatus($logStatus);
    }
}
