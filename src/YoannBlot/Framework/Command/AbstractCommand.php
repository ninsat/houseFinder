<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Command;

use Psr\Log\LogLevel;
use YoannBlot\Framework\Model\Exception\ParameterException;
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
     * @var string[] command parameters.
     */
    private $aParameters = [];

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
     * Set command parameters.
     *
     * @param array $aParameters parameters.
     */
    public function setParameters(array $aParameters): void
    {
        foreach ($aParameters as $iParameterPosition => $sParameter) {
            if (false !== strpos($sParameter, '-vvv')) {
                $this->getLogger()->setLevel(LogLevel::DEBUG);
            } elseif (false !== strpos($sParameter, '-vv')) {
                $this->getLogger()->setLevel(LogLevel::INFO);
            } elseif (false !== strpos($sParameter, '-v')) {
                $this->getLogger()->setLevel(LogLevel::WARNING);
            } else {
                $this->getLogger()->setLevel(LogLevel::ERROR);
            }
            $this->getLogger()->setOutput(true);

            if (false !== strpos($sParameter, '-v')) {
                unset($aParameters[$iParameterPosition]);
            }
        }

        $this->aParameters = array_values($aParameters);
    }

    /**
     * @return string[] parameters.
     */
    public function getParameters(): array
    {
        return $this->aParameters;
    }

    /**
     * Get a command parameter.
     *
     * @param int $iPosition parameter position.
     *
     * @return string parameter if found.
     *
     * @throws ParameterException parameter exception.
     */
    protected function getParameter($iPosition = 0): string
    {
        if (count($this->aParameters) < $iPosition + 1) {
            throw new ParameterException();
        }
        return $this->getParameters()[$iPosition];
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