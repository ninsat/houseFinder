<?php

namespace YoannBlot\Framework\Controller;

/**
 * Class AbstractController.
 *
 * @package YoannBlot\Framework\Controller
 */
abstract class AbstractController {

    /**
     * View directory name.
     */
    const VIEW_DIR_NAME = 'View';

    /**
     * Template extension file.
     */
    const TEMPLATE_EXT = '.php';

    /**
     * Default page name.
     */
    const DEFAULT_PAGE = 'index';

    /**
     * @var string current page
     */
    private $sCurrentPage = self::DEFAULT_PAGE;

    /**
     * Default page. Can be override if index page is defined in current controller.
     *
     * @return array empty array
     */
    protected function indexPage () : array {
        return [];
    }

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
     * @return string cleaned current class name.
     */
    private function getCurrentClassName () : string {
        $sClassName = get_class($this);
        $sClassName = substr($sClassName, strrpos($sClassName, '\\') + 1);
        $sClassName = substr($sClassName, 0, strpos($sClassName, 'Controller'));

        return $sClassName;
    }

    /**
     * @return string View directory
     */
    private function getViewDirectory () : string {
        $sDirectory = '';
        // TODO project directory path

        $sDirectory .= static::VIEW_DIR_NAME . DIRECTORY_SEPARATOR;
        $sDirectory .= $this->getCurrentClassName() . DIRECTORY_SEPARATOR;
        $sDirectory .= $this->getCurrentPage() . static::TEMPLATE_EXT;

        return $sDirectory;
    }

    /**
     * @return array data to send to view page.
     */
    private function getPageData (): array {
        $sPage = $this->getCurrentPage() . 'Page';

        return $this->$sPage();
    }
}