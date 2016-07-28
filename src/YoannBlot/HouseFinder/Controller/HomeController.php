<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;

/**
 * Class HomeController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/")
 */
class HomeController extends AbstractController {

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