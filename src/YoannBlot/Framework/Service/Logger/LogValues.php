<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\Logger;

/**
 * Class LogValues.
 * All possible values a Log can have.
 *
 * @package YoannBlot\Framework\Service\Logger
 * @author  Yoann Blot
 */
abstract class LogValues
{

    const NULL = 10;
    const DEBUG = 8;
    const INFO = 7;
    const NOTICE = 6;
    const WARNING = 5;
    const ERROR = 4;
    const CRITICAL = 3;
    const ALERT = 2;
    const EMERGENCY = 1;

    /**
     * Get the log level from string value.
     *
     * @param string $sLogLevel log level as string.
     *
     * @return int log level as value.
     */
    public static function get(string $sLogLevel): int
    {
        $sLogLevel = strtoupper($sLogLevel);
        switch ($sLogLevel) {
            case 'DEBUG':
                $iValue = static::DEBUG;
                break;
            case 'INFO':
                $iValue = static::INFO;
                break;
            case 'NOTICE':
                $iValue = static::NOTICE;
                break;
            case 'WARNING':
            case 'WARN':
                $iValue = static::WARNING;
                break;
            case 'ERROR':
                $iValue = static::ERROR;
                break;
            case 'CRITICAL':
                $iValue = static::CRITICAL;
                break;
            case 'ALERT':
                $iValue = static::ALERT;
                break;
            case 'EMERGENCY':
                $iValue = static::EMERGENCY;
                break;
            default :
                $iValue = static::NULL;
                break;
        }

        return $iValue;
    }

    /**
     * @param int $iLevel level mode.
     *
     * @return string mode.
     */
    public static function getMode(int $iLevel): string
    {
        switch ($iLevel) {
            case static::DEBUG:
                $sMode = 'DEBUG';
                break;
            case static::INFO:
                $sMode = 'INFO';
                break;
            case static::NOTICE:
                $sMode = 'NOTICE';
                break;
            case static::WARNING:
                $sMode = 'WARNING';
                break;
            case  static::ERROR:
                $sMode = 'ERROR';
                break;
            case static::CRITICAL:
                $sMode = 'CRITICAL';
                break;
            case static::ALERT:
                $sMode = 'ALERT';
                break;
            case static::EMERGENCY:
            default :
                $sMode = 'EMERGENCY';
                break;
        }

        return $sMode;
    }
}