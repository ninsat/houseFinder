<?php

namespace YoannBlot\Framework\Utils\Log;

use YoannBlot\Framework\Utils\File\Directory;
use YoannBlot\Framework\Utils\File\Loader;
use YoannBlot\Framework\Validator\Boolean;

/**
 * Class Log.
 * Used to keep a trace of everything on the server.
 *
 * @package YoannBlot\Framework\Utils\Log
 * @author  Yoann Blot
 */
class Log {

    const DEFAULT_MODE = LogValues::WARN;

    /**
     * Singleton.
     *
     * @var Log instance
     */
    private static $oInstance = null;

    /**
     * @return Log current instance.
     */
    public static function get () : Log {
        if (null === self::$oInstance) {
            self::$oInstance = new Log();
        }

        return self::$oInstance;
    }

    /**
     * Log level.
     *
     * @var int log level.
     */
    private $iLogLevel = self::DEFAULT_MODE;

    /**
     * Constructor.
     */
    private function __construct () {
        $this->init();
    }

    /**
     * Set the new log level.
     *
     * @param string $sLogLevel new log level.
     */
    public function setLevel (string $sLogLevel) {
        $iLogValue = LogValues::get($sLogLevel);

        if ($iLogValue <= LogValues::DEBUG) {
            $this->iLogLevel = $iLogValue;
        } else {
            $this->warn('Try to set a wrong log level : "' . $sLogLevel . '"');
        }
    }

    /**
     * Get the current log level.
     *
     * @return int log level.
     */
    public function getLevel (): int {
        return $this->iLogLevel;
    }

    /**
     * Initialize the log output files.
     */
    private function init () {
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);

        $bDebugMode = Boolean::getValue(Loader::get('debug'));

        if (!$bDebugMode) {
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            $sLogFile = $this->getFile();
            Directory::create(dirname($sLogFile));
            ini_set('error_log', $sLogFile);

        }
    }

    /**
     * @return string log file path
     */
    private function getFile () : string {
        return ROOT_PATH . 'var' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
    }

    /**
     * Write a log.
     *
     * @param string $sMessage message to display.
     * @param string $sMode    mode of log. Could be DEBUG / INFO / WARN / ERROR.
     */
    private function write (string $sMessage, string $sMode = 'INFO') {
        if ($this->isAllowed($sMode)) {
            $sMode = strtoupper($sMode);
            $sHeaders = date('[Y-m-d H:i:s]') . '   ' . $sMode . '   ';
            error_log($sHeaders . $sMessage . PHP_EOL, 3, $this->getFile());

            $sIpAddress = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : 'local';
            $sPath = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : 'test';
            $sMessage = 'IP : ' . $sIpAddress . ' / path : ' . $sPath;
            error_log($sHeaders . $sMessage . PHP_EOL, 3, $this->getFile());
        }
    }

    /**
     * Write a debug message.
     *
     * @param string $sMessage debug message to display.
     */
    public function debug (string $sMessage) {
        $this->write($sMessage, 'DEBUG');
    }

    /**
     * Write an info message.
     *
     * @param string $sMessage info message to display.
     */
    public function info (string $sMessage) {
        $this->write($sMessage, 'INFO');
    }

    /**
     * Write a warning message.
     *
     * @param string $sMessage warning message to display.
     */
    public function warn (string $sMessage) {
        $this->write($sMessage, 'WARN');
    }

    /**
     * Write an error message.
     *
     * @param string $sMessage error message to display.
     */
    public function error (string $sMessage) {
        $this->write($sMessage, 'ERROR');
    }

    /**
     * Check if we need to display the log or not.
     *
     * @param string $sMode mode of log. Could be DEBUG / INFO / WARN / ERROR.
     *
     * @return boolean true if log will be displayed, false otherwise.
     */
    private function isAllowed (string $sMode): bool {
        return ($this->getLevel() >= LogValues::get($sMode));
    }
}
