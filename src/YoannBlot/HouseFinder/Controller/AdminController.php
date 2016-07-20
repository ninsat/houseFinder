<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\HouseFinder\Model\City;

/**
 * Class AdminController.
 *
 * @package YoannBlot\HouseFinder\Controller
 */
class AdminController extends AbstractController {

    /**
     * @return array city
     */
    protected function cityPage () {
        $oCity = new City();
        // TODO get real city
        $oCity->setName('Poissy');
        $oCity->setPostalCode('78300');
        $oCity->setEnabled(true);

        return ['city' => $oCity];
    }
}