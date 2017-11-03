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
     * @param int $iCityId city id.
     * @return array
     *
     * @path("/([0-9]+)")
     */
    public function indexRoute (int $iCityId): array {
        $oCityRepository = new CityRepository();

        $oCity = $oCityRepository->get($iCityId);

        return [
            'city' => $oCity
        ];
    }

    /**
     * List of houses from city.
     *
     * @param int $iCityId city id.
     * @return array
     *
     * @path("/([0-9]+)/houses")
     */
    public function housesRoute (int $iCityId): array {
        $oCityRepository = new CityRepository();
        $oHouseRepository = new HouseRepository();

        $oCity = $oCityRepository->get($iCityId);
        $oLastHouses = $oHouseRepository->getLast($oCity);

        return [
            'cities' => $oCityRepository->getAll(),
            'city'   => $oCity,
            'houses' => $oLastHouses
        ];
    }
}