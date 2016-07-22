<?php
namespace YoannBlot\Framework\Utils\Log;

/**
 * Class LogValues.
 * All possible values a Log can have.
 *
 * @package YoannBlot\Framework\Utils\Log
 * @author  Yoann Blot
 */
abstract class LogValues {

    const NULL = 10;
    const DEBUG = 4;
    const INFO = 3;
    const WARN = 2;
    const ERROR = 1;

    /**
     * Get the log level from string value.
     *
     * @param string $sLogLevel log level as string.
     *
     * @return int log level as value.
     */
    public static function get (string $sLogLevel) : int {
        $iValue = self::NULL;
        $sLogLevel = strtoupper($sLogLevel);
        switch ($sLogLevel) {
            case 'DEBUG':
                $iValue = self::DEBUG;
                break;
            case 'INFO':
                $iValue = self::INFO;
                break;
            case  'WARN':
                $iValue = self::WARN;
                break;
            case 'ERROR':
                $iValue = self::ERROR;
                break;
        }

        return $iValue;
    }
}