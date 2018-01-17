<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Kernel;

use Doctrine\Common\Annotations\AnnotationRegistry;
use YoannBlot\Framework\Command\AbstractCommand;
use YoannBlot\Framework\Command\Exception\CommandNotFoundException;
use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Controller\DefaultController;
use YoannBlot\Framework\Controller\Exception\Redirect404Exception;
use YoannBlot\Framework\DependencyInjection\Container;

/**
 * Class Kernel
 *
 * @package YoannBlot\Framework\Kernel
 * @author  Yoann Blot
 */
class Kernel
{
    /**
     * @var Container service container.
     */
    private $oContainer = null;

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
        $this->autoloadAnnotations();
    }

    /**
     * @return Container service container.
     */
    private function getContainer(): Container
    {
        if (null === $this->oContainer) {
            $this->oContainer = new Container();
        }
        return $this->oContainer;
    }

    /**
     * Display current page.
     */
    private function display()
    {
        try {
            $oController = $this->selectController();
            $oController->autoSelectPage();
            $sOutput = $oController->displayPage();
            // TODO add access denied exception
        } catch (Redirect404Exception | \Exception $oException) {
            $oController = new DefaultController($this->getContainer()->getLogger(), false);
            $oController->setCurrentRoute(DefaultController::NOT_FOUND);
            try {
                $sOutput = $oController->displayPage();
            } catch (Redirect404Exception $oException) {
                $sOutput = '';
            }
        }

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
        $sPath = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '';
        $oSelectedController = $this->getContainer()->getController($sPath);

        if (null === $oSelectedController) {
            $this->getContainer()->getLogger()->warning("Path '$sPath' not found, redirect to 404 page.");
            throw new Redirect404Exception("Path '$sPath' not found, redirect to 404 page.");
        }

        return $oSelectedController;
    }

    /**
     * Select the right command from arguments.
     *
     * @param string $sCommandName command name.
     *
     * @return AbstractCommand command.
     * @throws CommandNotFoundException command not found.
     */
    private function selectCommand(string $sCommandName): ?AbstractCommand
    {
        $oSelectedCommand = $this->getContainer()->getCommand($sCommandName);
        if (null === $oSelectedCommand) {
            $this->getContainer()->getLogger()->warning("Command '$sCommandName' not found.");
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
            $oCommand = $this->selectCommand($argv[1]);
            if ($oCommand->run()) {
                $this->getContainer()->getLogger()->info("Command " . get_class($oCommand) . " run with success");
            } else {
                $this->getContainer()->getLogger()->error("Error running command " . get_class($oCommand));
            }
        } catch (CommandNotFoundException $oException) {
            echo "Cannot run command : " . $oException->getMessage();
        }
    }

    /**
     * Autoload all annotations files.
     */
    private function autoloadAnnotations(): void
    {
        $aNamespaces = [];
        $aAnnotationClasses = glob(SRC_PATH . 'YoannBlot/*/Model/DataBase/Annotation/*.php');
        foreach ($aAnnotationClasses as $sAnnotationClass) {
            $sAnnotationClass = str_replace('/', '\\', $sAnnotationClass);
            $sNamespace = str_replace(SRC_PATH, '', $sAnnotationClass);
            $sNamespace = substr($sNamespace, 0, strrpos($sNamespace, '\\'));
            if (!in_array($sNamespace, $aNamespaces)) {
                $this->getContainer()->getLogger()->debug("Add namespace '$sNamespace' to annotation register.");
                AnnotationRegistry::registerAutoloadNamespace($sNamespace, SRC_PATH);
                $aNamespaces[] = $sNamespace;
            }
            $sAnnotationClass = $sNamespace . substr($sAnnotationClass, strrpos($sAnnotationClass, '\\'));
            $sAnnotationClass = substr($sAnnotationClass, 0, strrpos($sAnnotationClass, '.'));
            if (!AnnotationRegistry::loadAnnotationClass($sAnnotationClass)) {
                $this->getContainer()->getLogger()->error("Failed autoloading Annotation class " . $sAnnotationClass);
            }
        }
    }
}