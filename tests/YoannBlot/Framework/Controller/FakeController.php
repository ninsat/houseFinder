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

    protected function validPage () {
        return [
            'title' => 'this is a title'
        ];
    }
}