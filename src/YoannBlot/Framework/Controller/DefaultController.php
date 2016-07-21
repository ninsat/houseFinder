<?php

namespace YoannBlot\Framework\Controller;

/**
 * Class DefaultController.
 *
 * @package YoannBlot\Framework\Controller
 *
 * @path("/default-controller/this-path-should-never-be-used/")
 */
class DefaultController extends AbstractController {

    /**
     * @return array
     */
    public function notFoundPage () : array {
        return [];
    }
}