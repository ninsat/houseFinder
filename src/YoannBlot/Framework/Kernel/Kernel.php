<?php

namespace YoannBlot\Framework\Kernel;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Controller\DefaultController;

/**
 * Class Kernel
 *
 * @package YoannBlot\Framework\Kernel
 */
class Kernel {

    /**
     * Kernel constructor.
     */
    public function __construct () {
        $oController = $this->selectController();
        $oController->displayPage();
    }

    /**
     * Select right controller.
     *
     * @return AbstractController selected controller.
     */
    private function selectController (): AbstractController {
        $oSelectedController = null;
        $sPath = $_SERVER['REQUEST_URI'];

        foreach (glob(SRC_PATH . '*/*/Controller/*Controller.php') as $sControllerPath) {
            if (false === strpos($sControllerPath, 'Abstract')) {
                $sControllerPath = str_replace([SRC_PATH, '.php'], '', $sControllerPath);
                $sControllerPath = str_replace('/', '\\', $sControllerPath);
                $oReflection = new \ReflectionClass($sControllerPath);
                $oController = $oReflection->newInstance();

                if ($oController->matchPath($sPath)) {
                    $oSelectedController = $oController;
                    break;
                }
            }
        }

        if (null === $oSelectedController) {
            $oSelectedController = new DefaultController();
            $oSelectedController->setCurrentPage('notFound');
        }

        return $oSelectedController;
    }
}