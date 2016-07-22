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
    private $sCurrentPage = self::DEFAULT_PAGE;

    /**
     * Check if given path is matching current controller.
     *
     * @param string $sPath path to check.
     *
     * @return bool true if given path is matching current controller.
     */
    public function matchPath (string $sPath) : bool {
        $oReflectionClass = new \ReflectionClass($this);
        $oDocComment = $oReflectionClass->getDocComment();
        preg_match_all('#@path\(\"(.*)\"\)\n#s', $oDocComment, $aPathAnnotations);

        if (count($aPathAnnotations[1]) > 0) {
            $sControllerPath = $aPathAnnotations[1][0];
            $bMatch = 0 === strpos($sPath, $sControllerPath);
        } else {
            Log::get()->error('You must add an annotation @path to your Controller');
            $bMatch = false;
        }

        return $bMatch;
    }

    public abstract function autoSelectPage ();

    /**
     * @return string current page
     */
    public function getCurrentPage (): string {
        return $this->sCurrentPage;
    }

    /**
     * Set the new current page.
     *
     * @param string $sCurrentPage current page.
     */
    public function setCurrentPage (string $sCurrentPage) {
        if ($this->isValidPage($sCurrentPage)) {
            $this->sCurrentPage = $sCurrentPage;
        }
    }

    /**
     * Check if the given page is valid or not.
     *
     * @param string $sPageName page name to check for validity.
     *
     * @return bool true if given page is valid, otherwise false.
     */
    private function isValidPage (string $sPageName) : bool {
        // check if method exists $sPageName.'Page'
        $sMethodName = $sPageName . 'Page';
        $bValid = method_exists($this, $sMethodName);

        return $bValid;
    }

    /**
     * @return array data to send to view page.
     */
    private function getPageData (): array {
        $sPage = $this->getCurrentPage() . 'Page';

        return $this->$sPage();
    }

    /**
     * Display current page.
     */
    public function displayPage () {
        ob_start();

        $oView = new View($this, $this->getPageData());
        $oView->display();

        return ob_get_clean();

    }

}