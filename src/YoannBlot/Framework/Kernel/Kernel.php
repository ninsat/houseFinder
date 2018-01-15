<?php

namespace YoannBlot\Framework\Kernel;

use YoannBlot\Framework\Command\AbstractCommand;
use YoannBlot\Framework\Command\Exception\CommandNotFoundException;
use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Controller\DefaultController;
use YoannBlot\Framework\Controller\Exception\Redirect404Exception;
use YoannBlot\Framework\Utils\Log\Log;

/**
 * Class Kernel
 *
 * @package YoannBlot\Framework\Kernel
 * @author  Yoann Blot
 */
class Kernel
{

    /**
     * Kernel constructor.
     *
     * @param bool $bAutoDisplay true if auto display mode enabled.
     */
    public function __construct(bool $bAutoDisplay = true)
    {
        if ($bAutoDisplay) {
            $this->display();
        }
    }

    /**
     * Display current page.
     */
    private function display()
    {
        try {
            $oController = $this->selectController();
            $oController->autoSelectPage();
        } catch (Redirect404Exception $oException) {
            $oController = new DefaultController();
            $oController->setCurrentRoute('notFound');
        }
        $sOutput = $oController->displayPage();

        echo $sOutput;
    }

    /**
     * Select right controller.
     *
     * @return AbstractController selected controller.
     * @throws Redirect404Exception controller not found
     */
    private function selectController(): AbstractController
    {
        /** @var AbstractController $oSelectedController */
        $oSelectedController = null;
        $sPath = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : null;

        if (null !== $sPath) {
            $aControllers = glob(SRC_PATH . 'YoannBlot/*/Controller/*Controller.php');
            foreach ($aControllers as $sControllerPath) {
                if (false === strpos($sControllerPath, 'Abstract')) {
                    $sControllerPath = str_replace([SRC_PATH, '.php'], '', $sControllerPath);
                    $sControllerPath = str_replace('/', '\\', $sControllerPath);
                    $oReflection = new \ReflectionClass($sControllerPath);

                    $oController = $oReflection->newInstance();
                    if ($oController instanceof AbstractController && $oController->matchPath($sPath)) {
                        if (null === $oSelectedController || strlen($oController->getControllerPattern()) > strlen($oSelectedController->getControllerPattern())) {
                            $oSelectedController = $oController;
                        }
                    }
                }
            }
        }

        if (null === $oSelectedController) {
            Log::get()->warn("Path '$sPath' not found, redirect to 404 page.");
            throw new Redirect404Exception("Path '$sPath' not found, redirect to 404 page.");
        }

        return $oSelectedController;
    }

    /**
     * Select the right command from arguments.
     *
     * @param array $argv command arguments.
     *
     * @return AbstractCommand command.
     * @throws CommandNotFoundException command not found.
     */
    private function selectCommand(array $argv): AbstractCommand
    {
        /** @var AbstractCommand $oSelectedCommand */
        $oSelectedCommand = null;
        $sCommandName = '';
        if (count($argv) > 1) {
            $sCommandName = $argv[1];

            $aCommands = glob(SRC_PATH . 'YoannBlot/*/Command/*/*Command.php');
            foreach ($aCommands as $sCommandPath) {
                if (false === strpos($sCommandPath, 'Abstract')) {
                    $sCommandPath = str_replace([SRC_PATH, '.php'], '', $sCommandPath);
                    $sCommandPath = str_replace('/', '\\', $sCommandPath);
                    $oReflection = new \ReflectionClass($sCommandPath);

                    $oCommand = $oReflection->newInstance();
                    if ($oCommand instanceof AbstractCommand && $sCommandName === $oCommand->getName()) {
                        $oSelectedCommand = $oCommand;
                    }
                }
            }
        }

        if (null === $oSelectedCommand) {
            Log::get()->warn("Command '$sCommandName' not found.");
            throw new CommandNotFoundException("Command '$sCommandName' not found.");
        }

        return $oSelectedCommand;
    }

    /**
     * Run the right command.
     *
     * @param array $argv command arguments.
     */
    public function runCommand(array $argv): void
    {
        try {
            $oCommand = $this->selectCommand($argv);
            if ($oCommand->run()) {
                Log::get()->info("Command " . get_class($oCommand) . " run with success");
            } else {
                Log::get()->error("Error running command " . get_class($oCommand));
            }
        } catch (CommandNotFoundException $oException) {
            echo "Cannot run command : " . $oException->getMessage();
        }
    }
}