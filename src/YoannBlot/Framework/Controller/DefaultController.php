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
     * @return array
     *
     * @path("(.*)")
     */
    public function notFoundRoute () : array {
        return [];
    }
}