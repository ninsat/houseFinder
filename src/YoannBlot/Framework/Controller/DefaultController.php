<?php

namespace YoannBlot\Framework\Controller;

/**
 * Class DefaultController.
 *
 * @package YoannBlot\Framework\Controller
 * @author  Yoann Blot
 *
 * @path("/default-controller/this-path-should-never-be-used/")
 */
class DefaultController extends AbstractController {

    /**
     * @inheritdoc
     */
    public function autoSelectPage () {
        $this->setCurrentPage('notFound');
    }

    /**
     * @return array
     */
    public function notFoundPage () : array {
        return [];
    }
}