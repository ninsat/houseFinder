<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\Logger;

use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Service\ConfigurationLoader\LoaderInterface;
use YoannBlot\Framework\Utils\File\Directory;
use YoannBlot\Framework\Validator\Boolean;

/**
 * Class LoggerService.
 * Used to keep a trace of everything on the server.
 *
 * @package YoannBlot\Framework\Service\Logger
 * @author  Yoann Blot
 */
class LoggerService implements LoggerInterface
{

    const DEFAULT_MODE = LogValues::WARNING;

    /**
     * Log level.
     *
     * @var int log level.
     */
    private $iLogLevel = self::DEFAULT_MODE;

    /**
     * LoggerService constructor.
     *
     * @param LoaderInterface $oLoaderService loader service.
     */
    public function __construct(LoaderInterface $oLoaderService)
    {
        $this->init($oLoaderService);
    }

    /**
     * Set the new log level.
     *
     * @param string $sLogLevel new log level.
     */
    public function setLevel(string $sLogLevel)
    {
        $iLogValue = LogValues::get($sLogLevel);

        if ($iLogValue <= LogValues::DEBUG) {
            $this->iLogLevel = $iLogValue;
        } else {
            $this->warning('Try to set a wrong log level : "' . $sLogLevel . '"');
        }
    }

    /**
     * Get the current log level.
     *
     * @return int log level.
     */
    public function getLevel(): int
    {
        return $this->iLogLevel;
    }

    /**
     * Initialize the log output files.
     *
     * @param LoaderInterface $oLoaderService loader service.
     */
    private function init(LoaderInterface $oLoaderService)
    {
        error_reporting(E_ALL);
        ini_set('error_reporting', '' . E_ALL);

        $bDebugMode = Boolean::getValue($oLoaderService->get('debug'));
        $sLogFile = $this->getFile();
        Directory::create(dirname($sLogFile));
        ini_set('error_log', $sLogFile);

        if (!$bDebugMode) {
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
        }
    }

    /**
     * @return string log file path
     */
    private function getFile(): string
    {
        return ROOT_PATH . 'var' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
    }

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->isAllowed($level)) {
            $sMode = strtoupper(LogValues::getMode($level));
            $sHeaders = date('[Y-m-d H:i:s]') . '   ' . $sMode . '   ';
            error_log($sHeaders . $message . PHP_EOL, 3, $this->getFile());

            $sIpAddress = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : 'local';
            $sPath = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : 'test';
            $message = 'IP : ' . $sIpAddress . ' / path : ' . $sPath;
            error_log($sHeaders . $message . PHP_EOL, 3, $this->getFile());
        }
    }

    /**
     * @inheritdoc
     */
    public function emergency($message, array $context = [])
    {
        $this->log(LogValues::EMERGENCY, $message);
    }

    /**
     * @inheritdoc
     */
    public function alert($message, array $context = [])
    {
        $this->log(LogValues::ALERT, $message);
    }

    /**
     * @inheritdoc
     */
    public function critical($message, array $context = [])
    {
        $this->log(LogValues::CRITICAL, $message);
    }

    /**
     * @inheritdoc
     */
    public function error($message, array $context = [])
    {
        $this->log(LogValues::ERROR, $message);
    }

    /**
     * @inheritdoc
     */
    public function warning($message, array $context = [])
    {
        $this->log(LogValues::WARNING, $message);
    }

    /**
     * @inheritdoc
     */
    public function notice($message, array $context = [])
    {
        $this->log(LogValues::NOTICE, $message);
    }

    /**
     * @inheritdoc
     */
    public function info($message, array $context = [])
    {
        $this->log(LogValues::INFO, $message);
    }

    /**
     * @inheritdoc
     */
    public function debug($message, array $context = [])
    {
        $this->log(LogValues::DEBUG, $message);
    }

    /**
     * Check if we need to display the log or not.
     *
     * @param int $iLevel level mode of log. Could be DEBUG / INFO / WARN / ERROR...
     *
     * @return boolean true if log will be displayed, false otherwise.
     */
    private function isAllowed(int $iLevel): bool
    {
        return ($this->getLevel() >= $iLevel);
    }
}
