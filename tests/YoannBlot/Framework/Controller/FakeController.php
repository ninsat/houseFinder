<?php

namespace YoannBlot\Framework\Controller;

/**
 * Class FakeController
 *
 * @package YoannBlot\Framework\Controller
 * @author  Yoann Blot
 *
 * @path("/fake/")
 */
class FakeController extends AbstractController {

    /**
     * @inheritdoc
     */
    public function autoSelectPage () {
    }

    /**
     * @return array
     *
     * @path("")
     */
    public function validRoute () {
        return [
            'title' => 'this is a title'
        ];
    }
}