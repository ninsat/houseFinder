<?php
declare(strict_types=1);

namespace YoannBlot\Framework\DependencyInjection;

use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Command\AbstractCommand;
use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Model\DataBase\ConfigurationConstants;
use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\Framework\Service\ConfigurationLoader\LoaderInterface;
use YoannBlot\Framework\Service\ConfigurationLoader\LoaderService;

/**
 * Class Container.
 *
 * @package YoannBlot\Framework\DependencyInjection
 */
class Container
{

    /**
     * @var array services.
     */
    private $aServices = [];

    /**
     * @var array parameters.
     */
    private $aParameters = [];

    /**
     * Container constructor.
     */
    public function __construct()
    {
        // default services
        $this->aServices[LoaderInterface::class] = new LoaderService();

        $this->initServices();
    }

    /**
     * Initialize all available services.
     */
    private function initServices(): void
    {
        /** @var LoaderInterface $oLoader */
        $oLoader = $this->getService(LoaderInterface::class);

        $oLoader->load(ConfigurationConstants::PATH);
        $this->aParameters = $oLoader->getAll();

        // get all service configuration files
        $aServiceFiles = glob(SRC_PATH . 'YoannBlot/*/Resources/config/services.ini');
        foreach ($aServiceFiles as $sServiceFile) {
            if ($oLoader->load($sServiceFile)) {
                foreach ($oLoader->getAll('SERVICES') as $sServiceName => $sServiceClass) {
                    if (!array_key_exists($sServiceName, $this->aServices)) {
                        $oReflection = new \ReflectionClass($sServiceClass);
                        $oService = $oReflection->newInstanceArgs($this->getAutowireParameters($oReflection));

                        $this->aServices[$sServiceName] = $oService;
                    }
                }
            }
        }
    }

    /**
     * Get the right service from its interface/class name.
     *
     * @param string $sServiceName service name.
     *
     * @return \object|null service.
     */
    private function getService(string $sServiceName)
    {
        $oFoundService = null;

        if (array_key_exists($sServiceName, $this->aServices)) {
            $oFoundService = $this->aServices[$sServiceName];
        } else {
            foreach ($this->aServices as $oService) {
                if ($sServiceName === get_class($oService)) {
                    $oFoundService = $oService;
                    break;
                }
            }
        }

        return $oFoundService;
    }

    /**
     * Automatically found and inject services.
     *
     * @param \ReflectionClass $oCurrentService service.
     *
     * @return array all necessary/known service parameters.
     */
    private function getAutowireParameters(\ReflectionClass $oCurrentService): array
    {
        $aServiceParameters = [];
        if (!$oCurrentService->isAbstract() && null !== $oCurrentService->getConstructor()) {
            $aConstructorParameters = $oCurrentService->getConstructor()->getParameters();

            foreach ($aConstructorParameters as $oConstructorParameter) {
                $oService = null;
                if (null !== $oConstructorParameter->getType()) {
                    $oService = $this->getService($oConstructorParameter->getType()->getName());
                    if (null !== $oService) {
                        $aServiceParameters [] = $oService;
                    } elseif ('repositories' === $oConstructorParameter->getName()) {
                        $aServiceParameters [] = $this->getAllRepositories();
                    }
                } elseif (array_key_exists($oConstructorParameter->getName(), $this->aParameters)) {
                    $aServiceParameters [] = $this->aParameters[$oConstructorParameter->getName()];
                }
            }
        }
        return $aServiceParameters;
    }

    /**
     * Get a repository by its name.
     *
     * @param string $sRepositoryName repository name.
     *
     * @return null|AbstractRepository repository.
     */
    public function getRepository(string $sRepositoryName): ?AbstractRepository
    {
        /** @var AbstractRepository $oService */
        $oService = $this->getService($sRepositoryName);

        return $oService;
    }

    /**
     * @return AbstractRepository[] all repositories.
     */
    private function getAllRepositories(): array
    {
        $aRepositories = [];
        foreach ($this->aServices as $sServiceName => $oService) {
            if ($oService instanceof AbstractRepository) {
                $aRepositories[] = $oService;
            }
        }
        return $aRepositories;
    }

    /**
     * Get the right controller.
     *
     * @param string $sPath route path.
     *
     * @return null|AbstractController controller if found, otherwise null.
     */
    public function getController(string $sPath): ?AbstractController
    {
        static $aInstances = [];
        if (!array_key_exists($sPath, $aInstances)) {
            $aControllers = glob(SRC_PATH . 'YoannBlot/*/Controller/*Controller.php');
            /** @var AbstractController $oSelectedController */
            $oSelectedController = null;
            foreach ($aControllers as $sControllerPath) {
                if (false === strpos($sControllerPath, 'Abstract')) {
                    $sControllerPath = str_replace([SRC_PATH, '.php'], '', $sControllerPath);
                    $sControllerPath = str_replace('/', '\\', $sControllerPath);
                    $oReflection = new \ReflectionClass($sControllerPath);

                    $oController = $oReflection->newInstanceArgs($this->getAutowireParameters($oReflection));
                    if (
                        ($oController instanceof AbstractController && $oController->matchPath($sPath))
                        &&
                        (null === $oSelectedController || strlen($oController->getControllerPattern()) > strlen($oSelectedController->getControllerPattern()))
                    ) {
                        $oSelectedController = $oController;
                    }
                }
            }
            $aInstances[$sPath] = $oSelectedController;
        }

        return $aInstances[$sPath];
    }

    /**
     *
     * @param string $sCommandName command name.
     *
     * @return null|AbstractCommand matched command.
     */
    public function getCommand(string $sCommandName): ?AbstractCommand
    {
        static $aInstances = [];
        $oSelectedCommand = null;
        if (array_key_exists($sCommandName, $aInstances)) {
            $oSelectedCommand = $aInstances[$sCommandName];
        } else {
            $aCommands = glob(SRC_PATH . 'YoannBlot/*/Command/*/*Command.php');
            foreach ($aCommands as $sCommandPath) {
                if (false === strpos($sCommandPath, 'Abstract')) {
                    $sCommandPath = str_replace([SRC_PATH, '.php'], '', $sCommandPath);
                    $sCommandPath = str_replace('/', '\\', $sCommandPath);
                    $oReflection = new \ReflectionClass($sCommandPath);

                    $oCommand = $oReflection->newInstanceArgs($this->getAutowireParameters($oReflection));
                    if ($oCommand instanceof AbstractCommand && $sCommandName === $oCommand->getName()) {
                        $oSelectedCommand = $oCommand;
                        break;
                    }
                }
            }
        }

        return $oSelectedCommand;
    }

    /**
     * Get the logger service.
     *
     * @return LoggerInterface logger.
     */
    public function getLogger(): LoggerInterface
    {
        /** @var LoggerInterface $oService */
        $oService = $this->getService(LoggerInterface::class);

        return $oService;
    }
}