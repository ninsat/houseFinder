<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Controller\Exception\Redirect404Exception;
use YoannBlot\Framework\Model\Exception\DataBaseException;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;

/**
 * Class HouseController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/house")
 */
class HouseController extends AbstractController
{

    /**
     * House page.
     *
     * @param int $iHouseId house id.
     * @return array
     *
     * @path("/([0-9]+)")
     * @throws Redirect404Exception house not found.
     */
    public function indexRoute(int $iHouseId): array
    {
        $oHouseRepository = new HouseRepository();

        try {
            $oHouse = $oHouseRepository->get($iHouseId);
        } catch (DataBaseException $e) {
            throw new Redirect404Exception("House not found for id '$iHouseId'.");
        }

        return [
            'house' => $oHouse
        ];
    }
}