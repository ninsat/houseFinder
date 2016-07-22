<?php

namespace YoannBlot\Framework\Kernel;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Controller\DefaultController;
use YoannBlot\Framework\Controller\Exception\Redirect404Exception;

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
        $this->display();
    }

    private function display () {
        try {
            $oController = $this->selectController();
            $sOutput = $oController->displayPage();
        } catch (Redirect404Exception $oException) {
            $oController = new DefaultController();
            $oController->setCurrentPage('notFound');
            $sOutput = $oController->displayPage();
        }

        echo $sOutput;
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
                    $oSelectedController->autoSelectPage();
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