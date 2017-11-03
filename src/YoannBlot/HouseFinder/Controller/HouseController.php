<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;

/**
 * Class HouseController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/house")
 */
class HouseController extends AbstractController {

    /**
     * House page.
     *
     * @param int $iHouseId house id.
     * @return array
     *
     * @path("/([0-9]+)")
     */
    public function indexRoute (int $iHouseId): array {
        $oHouseRepository = new HouseRepository();

        $oHouse = $oHouseRepository->get($iHouseId);

        return [
            'house' => $oHouse
        ];
    }
}