<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Controller;

use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;
use YoannBlot\HouseFinder\Model\Repository\Helper\CityTrait;
use YoannBlot\HouseFinder\Model\Repository\Helper\HouseTrait;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;
use YoannBlot\HouseFinder\Service\HouseImages\HouseImagesService;
use YoannBlot\HouseFinder\Service\HouseImages\HouseImagesTrait;

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

    use CityTrait, HouseTrait,
        HouseImagesTrait;

    /**
     * CityController constructor.
     *
     * @param LoggerInterface $oLogger logger.
     * @param bool $debug debug mode.
     * @param CityRepository $oCityRepository city repository.
     * @param HouseRepository $oHouseRepository house repository.
     * @param HouseImagesService $oHouseImages house images service.
     */
    public function __construct(
        LoggerInterface $oLogger,
        $debug,
        CityRepository $oCityRepository,
        HouseRepository $oHouseRepository,
        HouseImagesService $oHouseImages
    ) {
        parent::__construct($oLogger, $debug);
        $this->oCityRepository = $oCityRepository;
        $this->oHouseRepository = $oHouseRepository;
        $this->oHouseImagesService = $oHouseImages;
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
        foreach ($oLastHouses as $oHouse) {
            $this->getHouseImages()->loadOne($oHouse);
        }
        $aCitites = $this->getCityRepository()->getAll();

        return [
            'cities' => $aCitites,
            'city' => $oCity,
            'houses' => $oLastHouses
        ];
    }
}