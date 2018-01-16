<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Command;

use YoannBlot\Framework\Service\Logger\LoggerService;
use YoannBlot\Framework\Service\Logger\LoggerTrait;

/**
 * Class AbstractCommand.
 *
 * @package YoannBlot\Framework\Command
 */
abstract class AbstractCommand
{
    use LoggerTrait;

    const DIRECTORY_SEPARATOR = ':';
    const FILE_SEPARATOR = '-';

    /**
     * AbstractCommand constructor.
     *
     * @param LoggerService $oLogger logger.
     */
    public function __construct(LoggerService $oLogger)
    {
        $this->oLogger = $oLogger;
    }

    /**
     * Get the current command name.
     * Packages names are separated by ":", file name's separator is "-".
     *
     * @return string command name.
     */
    public function getName(): string
    {
        $sCommandName = get_called_class();
        $sDirectoryName = 'Command' . DIRECTORY_SEPARATOR;
        $iLastSeparatorPosition = strrpos($sCommandName, DIRECTORY_SEPARATOR);

        $sCommandPath = substr($sCommandName, 0, $iLastSeparatorPosition);
        $sCommandPath = substr($sCommandPath, strpos($sCommandPath, $sDirectoryName) + strlen($sDirectoryName));
        // directory name
        if (false !== strpos($sCommandPath, DIRECTORY_SEPARATOR)) {
            $aDirectories = [];
            foreach (explode(DIRECTORY_SEPARATOR, $sCommandPath) as $sDirectory) {
                $aDirectories [] = strtolower($sDirectory);
            }
        } else {
            $aDirectories = [strtolower($sCommandPath)];
        }
        $sCommandFullName = implode(static::DIRECTORY_SEPARATOR, $aDirectories) . static::DIRECTORY_SEPARATOR;

        // get class name
        $sFileName = substr($sCommandName, $iLastSeparatorPosition + 1);
        $sFileName = str_replace('Command', '', $sFileName);

        $aWords = [];
        foreach (preg_split('/(?=[A-Z])/', $sFileName) as $sWord) {
            if (strlen($sWord) > 0) {
                $aWords [] = strtolower($sWord);
            }
        }

        $sCommandFileName = implode(static::FILE_SEPARATOR, $aWords);


        return $sCommandFullName . $sCommandFileName;
    }

    /**
     * Run current command.
     *
     * @return bool true if success, otherwise false.
     */
    public abstract function run(): bool;
}