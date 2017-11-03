<?php

namespace YoannBlot\Framework\Controller;

use YoannBlot\Framework\Controller\Exception\Redirect404Exception;
use YoannBlot\Framework\Utils\Log\Log;
use YoannBlot\Framework\View\View;

/**
 * Class AbstractController.
 *
 * @package YoannBlot\Framework\Controller
 * @author  Yoann Blot
 */
abstract class AbstractController {

    /**
     * Default page name.
     */
    const DEFAULT_PAGE = 'index';

    /**
     * @var string current page
     */
    private $sCurrentRoute = self::DEFAULT_PAGE;
    /**
     * @var array route parameters.
     */
    private $aRouteParameters = [];

    /**
     * Get the controller pattern.
     *
     * @return string controller pattern.
     */
    public function getControllerPattern (): string {
        $oReflectionClass = new \ReflectionClass($this);
        $oDocComment = $oReflectionClass->getDocComment();
        preg_match_all('#@path\(\"(.*)\"\)#s', $oDocComment, $aPathAnnotations);
        if (count($aPathAnnotations[1]) > 0) {
            $sControllerPattern = $aPathAnnotations[1][0];
        } else {
            $sControllerPattern = '';
            Log::get()->error('You must add an annotation @path to your Controller ' . get_class($this));
        }

        return $sControllerPattern;
    }

    /**
     * Check if given path is matching current controller.
     *
     * @param string $sPath path to check.
     *
     * @return bool true if given path is matching current controller.
     */
    public function matchPath (string $sPath): bool {
        $sControllerPath = $this->getControllerPattern();

        if ('' !== $sControllerPath) {
            $bMatch = 0 === strpos($sPath, $sControllerPath);
        } else {
            $bMatch = false;
        }

        return $bMatch;
    }

    /**
     * Automatic selection of a page.
     *
     * @throws Redirect404Exception
     */
    public function autoSelectPage () {
        $oReflectionClass = new \ReflectionClass($this);
        $bFound = false;
        foreach ($oReflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $oMethod) {
            // get all method xxxRoute()
            if (false !== strrpos($oMethod->getName(), 'Route')) {
                $sRoute = substr($oMethod->getName(), 0, strrpos($oMethod->getName(), 'Route'));
                $sDocComment = $oMethod->getDocComment();
                // get @path() from comment
                preg_match_all('#@path\(\"(.*)\"\)#s', $sDocComment, $aPathAnnotations);
                if (count($aPathAnnotations[1]) > 0) {
                    $sPattern = $aPathAnnotations[1][0];

                    // remove controller path from current path
                    $sCurrentPath = str_replace($this->getControllerPattern(), '', $_SERVER['REQUEST_URI']);

                    //  check if current path is valid
                    if (1 === preg_match("#^$sPattern$#", $sCurrentPath, $aMatchedParameters)) {
                        // remove first parameter (matched route)
                        array_shift($aMatchedParameters);
                        // if valid : redirect to page
                        $this->setCurrentRoute($sRoute, $aMatchedParameters);
                        $bFound = true;
                        break;
                    }
                }
            }
        }

        if (!$bFound) {
            throw new Redirect404Exception("No valid route in controller " . get_class($this));
        }
    }

    /**
     * @return string current page/route
     */
    public function getCurrent (): string {
        return $this->sCurrentRoute;
    }

    /**
     * Set the new current route.
     *
     * @param string $sCurrentRoute current route.
     * @param array $aParameters parameters to route.
     */
    public function setCurrentRoute (string $sCurrentRoute, array $aParameters = []) {
        if ($this->isRouteValid($sCurrentRoute)) {
            $this->sCurrentRoute = $sCurrentRoute;
            $this->aRouteParameters = $aParameters;
        }
    }

    /**
     * Check if the given route is valid or not.
     *
     * @param string $sPageName page name to check for validity.
     *
     * @return bool true if given page is valid, otherwise false.
     */
    private function isRouteValid (string $sPageName): bool {
        // check if method exists $sPageName.'Route'
        $sMethodName = $sPageName . 'Route';
        $bValid = method_exists($this, $sMethodName);

        return $bValid;
    }

    /**
     * @return array data to send to view page.
     */
    private function getRouteData (): array {
        $sPage = $this->getCurrent() . 'Route';

        $oMethod = new \ReflectionMethod($this, $sPage);

        return $oMethod->invokeArgs($this, $this->aRouteParameters);
    }

    /**
     * Display current page.
     */
    public function displayPage () {
        ob_start();

        $oView = new View($this, $this->getRouteData());
        $oView->display();

        return ob_get_clean();
    }

}