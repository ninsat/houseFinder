<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\Logger;

use Psr\Log\LogLevel;

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
        $sLogLevel = strtolower($sLogLevel);
        switch ($sLogLevel) {
            case LogLevel::DEBUG:
                $iValue = static::DEBUG;
                break;
            case LogLevel::INFO:
                $iValue = static::INFO;
                break;
            case LogLevel::NOTICE:
                $iValue = static::NOTICE;
                break;
            case LogLevel::WARNING:
                $iValue = static::WARNING;
                break;
            case LogLevel::ERROR:
                $iValue = static::ERROR;
                break;
            case LogLevel::CRITICAL:
                $iValue = static::CRITICAL;
                break;
            case LogLevel::ALERT:
                $iValue = static::ALERT;
                break;
            case LogLevel::EMERGENCY:
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
                $sMode = LogLevel::DEBUG;
                break;
            case static::INFO:
                $sMode = LogLevel::INFO;
                break;
            case static::NOTICE:
                $sMode = LogLevel::NOTICE;
                break;
            case static::WARNING:
                $sMode = LogLevel::WARNING;
                break;
            case  static::ERROR:
                $sMode = LogLevel::ERROR;
                break;
            case static::CRITICAL:
                $sMode = LogLevel::CRITICAL;
                break;
            case static::ALERT:
                $sMode = LogLevel::ALERT;
                break;
            case static::EMERGENCY:
            default :
                $sMode = LogLevel::EMERGENCY;
                break;
        }

        return $sMode;
    }
}