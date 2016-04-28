<?php
namespace App\Libs;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Log\Writer;

class BLogger
{
    // 所有的LOG都要求在这里注册
    const LOG_ERROR     = 'error';
    const LOG_REQUEST   = 'request';
    const LOG_SQL       = 'sql';
    const LOG_SMS       = 'sms';

    private static $loggers = array();

    // 获取一个实例
    public static function getLogger($type = self::LOG_ERROR, $day = 30)
    {
        if (empty(self::$loggers[$type])) {
            self::$loggers[$type] = new Writer(new Logger($type));
        }
        $log = self::$loggers[$type];
        $log->useDailyFiles(storage_path().'/logs/'. $type .'.log', $day);
        return $log;
    }
}