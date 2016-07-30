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

    /**
     * City page.
     *
     * @return array
     *
     * @path("/([0-9]+)")
     */
    public function indexRoute () : array {
        $oCityRepository = new CityRepository();

        $iCityId = 16;
        // TODO get city id from URL @path("/([0-9]+)/houses")

        $oCity = $oCityRepository->get($iCityId);

        return [
            'city' => $oCity
        ];
    }

    /**
     * List of houses from city.
     *
     * @return array
     *
     * @path("/([0-9]+)/houses")
     */
    public function housesRoute () : array {
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