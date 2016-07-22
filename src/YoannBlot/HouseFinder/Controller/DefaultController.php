<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;

/**
 * Class DefaultController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/")
 */
class DefaultController extends AbstractController {

    public function autoSelectPage () {
        $this->setCurrentPage('index');
    }

    public function indexPage () : array {
        $oCityRepository = new CityRepository();

        return [
            'cities' => $oCityRepository->getAll()
        ];
    }
}