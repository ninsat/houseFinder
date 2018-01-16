<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Controller;

use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;
use YoannBlot\HouseFinder\Model\Repository\Helper\CityTrait;
use YoannBlot\HouseFinder\Model\Repository\Helper\HouseTrait;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;

/**
 * Class CityController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/city")
 */
class CityController extends AbstractController
{

    use CityTrait, HouseTrait;

    /**
     * CityController constructor.
     *
     * @param LoggerInterface $oLogger logger.
     * @param bool $debug debug mode.
     * @param CityRepository $oCityRepository city repository.
     * @param HouseRepository $oHouseRepository house repository.
     */
    public function __construct(
        LoggerInterface $oLogger,
        $debug,
        CityRepository $oCityRepository,
        HouseRepository $oHouseRepository
    ) {
        parent::__construct($oLogger, $debug);
        $this->oCityRepository = $oCityRepository;
        $this->oHouseRepository = $oHouseRepository;
    }


    /**
     * City page.
     *
     * @param int $iCityId city id.
     * @return array
     *
     * @path("/([0-9]+)")
     */
    public function indexRoute(int $iCityId): array
    {
        $oCity = $this->getCityRepository()->get($iCityId);

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
    public function housesRoute(int $iCityId): array
    {
        $oCity = $this->getCityRepository()->get($iCityId);
        $oLastHouses = $this->getHouseRepository()->getLast($oCity);

        return [
            'cities' => $this->getCityRepository()->getAll(),
            'city' => $oCity,
            'houses' => $oLastHouses
        ];
    }
}