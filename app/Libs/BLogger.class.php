<?php
namespace App\Libs;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Log\Writer;

class BLogger
{
    // 所有的LOG都要求在这里注册
    const LOG_ERROR         = 'ERROR';
    const LOG_REQUEST       = 'REQUEST';
    const LOG_RESPONSE      = 'RESPONSE';
    const LOG_SQL           = 'SQL';
    const LOG_SMS           = 'SMS';
    const LOG_WECHAT_PAY    = 'WECHAT_PAY';
    const LOG_JPUSH         = 'JPUSH';

    private static $loggers = array();

    // 记录日志
    public static function getLogger($type = self::LOG_ERROR, $day = 30)
    {
        if (empty(self::$loggers[$type])) {
            self::$loggers[$type] = new Writer(new Logger($type));
        }
        $log = self::$loggers[$type];
        $log->useDailyFiles(storage_path().'/logs/'. $type .'.log', $day );
        return $log;
    }

    // 记录输入输出日志
    public static function getInOutLogger($type = self::LOG_REQUEST, $day = 30)
    {
        $filename = self::LOG_REQUEST;
        
        if (empty(self::$loggers[$type])) {
            self::$loggers[$type] = new Writer(new Logger($type));
        }
        $log = self::$loggers[$type];
        $log->useDailyFiles(storage_path().'/logs/'. $filename .'.log', $day );
        return $log;
    }

    // 记录mysql语句日志
    public static function setSqlLogger($type = self::LOG_SQL, $day = 30)
    {
        $filename = self::LOG_SQL;

        if (empty(self::$loggers[$type])) {
            self::$loggers[$type] = new Writer(new Logger($type));
        }
        $log = self::$loggers[$type];
        $log->useDailyFiles(storage_path().'/logs/'. $filename .'.log', $day );
        return $log;
    }
}