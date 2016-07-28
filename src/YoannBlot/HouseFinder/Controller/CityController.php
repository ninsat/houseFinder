<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;

/**
 * Class CityController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/city")
 */
class CityController extends AbstractController {

    public function autoSelectPage () {
        $this->setCurrentPage('houses');
    }

    public function indexPage () : array {
        $oCityRepository = new CityRepository();

        $iCityId = 16;
        // TODO get city id from URL @path("/([0-9]+)/houses")

        $oCity = $oCityRepository->get($iCityId);

        return [
            'city' => $oCity
        ];
    }

    public function housesPage () : array {
        $oCityRepository = new CityRepository();
        $oHouseRepository = new HouseRepository();

        $iCityId = 16;
        // TODO get city id from URL @path("/([0-9]+)/houses")

        $oCity = $oCityRepository->get($iCityId);
        $oLastHouses = $oHouseRepository->getLast($oCity);

        return [
            'city'   => $oCity,
            'houses' => $oLastHouses
        ];
    }
}