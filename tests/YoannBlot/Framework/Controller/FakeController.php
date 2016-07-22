<?php

namespace YoannBlot\Framework\Controller;

/**
 * Class FakeController
 *
 * @package YoannBlot\Framework\Controller
 *
 * @path("/fake/")
 */
class FakeController extends AbstractController {

    /**
     * @inheritdoc
     */
    public function autoSelectPage () {
    }

    protected function validPage () {
        return [
            'title' => 'this is a title'
        ];
    }
}