<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseFinder;

use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\House;
use YoannBlot\HouseFinder\Model\Entity\User;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;
use YoannBlot\HouseFinder\Model\Repository\Helper\CityTrait;
use YoannBlot\HouseFinder\Model\Repository\Helper\HouseTrait;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;
use YoannBlot\HouseFinder\Service\HouseCache\HouseCacheService;
use YoannBlot\HouseFinder\Service\HouseCache\HouseCacheTrait;
use YoannBlot\HouseFinder\Service\HouseCrawler\HouseCrawlerInterface;
use YoannBlot\HouseFinder\Service\HouseImages\HouseImagesService;
use YoannBlot\HouseFinder\Service\HouseImages\HouseImagesTrait;

/**
 * Class AbstractHouseFinder.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder
 */
abstract class AbstractHouseFinder implements HouseCrawlerInterface
{

    const HTML_CACHE = 'links.html';
    const JSON_CACHE = 'links.json';

    use HouseCacheTrait, HouseImagesTrait,
        HouseTrait, CityTrait;

    /**
     * @var User user.
     */
    private $oUser;

    /**
     * AbstractHouseFinder constructor.
     *
     * @param HouseCacheService $oCacheService cache service.
     * @param HouseImagesService $oHouseImagesService house images service.
     * @param HouseRepository $oHouseRepository house repository.
     * @param CityRepository $oCityRepository city repository.
     */
    public function __construct(
        HouseCacheService $oCacheService,
        HouseImagesService $oHouseImagesService,
        HouseRepository $oHouseRepository,
        CityRepository $oCityRepository
    ) {
        $this->oHouseCacheService = $oCacheService;
        $this->oHouseImagesService = $oHouseImagesService;
        $this->oHouseRepository = $oHouseRepository;
        $this->oCityRepository = $oCityRepository;
    }

    /**
     * Parse current content to retrieve all matched URLs.
     *
     * @return string[] matched URLs.
     */
    protected abstract function getUrls(): array;

    /**
     * @return House house.
     */
    protected abstract function getHouse(): House;

    /**
     * Get the current city object.
     *
     * @return City city.
     */
    protected abstract function parseCity(): City;

    /**
     * @inheritdoc
     */
    public function getUser(): User
    {
        return $this->oUser;
    }

    /**
     * @return City city.
     */
    private function getCity(): City
    {
        $oCity = $this->parseCity();
        if ('' !== $oCity->getPostalCode()) {
            $oFoundCity = $this->getCityRepository()->getOneByPostalCode($oCity->getPostalCode());
        } elseif ('' !== $oCity->getName()) {
            $oFoundCity = $this->getCityRepository()->getOneByName($oCity->getName());
        }
        if (null === $oFoundCity) {
            $oFoundCity = $this->getCityRepository()->insert($oCity);
        }

        return $oFoundCity;
    }

    /**
     * Check if current city is in user preferences.
     *
     * @param City $oCity city to test.
     *
     * @return bool true if city is valid, otherwise false.
     */
    private function isValidCity(City $oCity): bool
    {
        $bValid = false;
        foreach ($this->getUser()->getCities() as $oUserCity) {
            if ($oCity->getId() === $oUserCity->getId()) {
                $bValid = true;
                break;
            }
        }

        return $bValid;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        $sName = get_called_class();
        $sName = substr($sName, strrpos($sName, '\\') + 1);
        $sName = substr($sName, 0, strrpos($sName, 'Service'));

        return $sName;
    }

    /**
     * @return string cache directory.
     */
    private function getCacheDirectory(): string
    {
        return $this->getUser()->getId() . DIRECTORY_SEPARATOR . $this->getName() . DIRECTORY_SEPARATOR;
    }

    /**
     * Get the unique ID of given URL.
     *
     * @param string $sUrl url to retrieve unique id.
     *
     * @return string unique id.
     */
    protected abstract function getUniqueId(string $sUrl): string;

    /**
     * Get the house cache path.
     *
     * @param string $sUrl url.
     *
     * @return string house path.
     */
    protected function getHousePath(string $sUrl): string
    {
        return $this->getName() . DIRECTORY_SEPARATOR . $this->getUniqueId($sUrl) . '.html';
    }

    /**
     * @inheritdoc
     */
    public function processLinks(User $oUser): bool
    {
        $bSuccess = true;
        $this->oUser = $oUser;

        // cache HTML content
        $sCacheFile = $this->getCacheDirectory() . static::HTML_CACHE;
        if (!$this->getHouseCache()->isValid($sCacheFile)) {
            $bSuccess = $this->getHouseCache()->save(file_get_contents($this->generateUrl()), $sCacheFile);
        }

        $aUrls = $this->getUrls();
        $sCacheFile = $this->getCacheDirectory() . static::JSON_CACHE;
        if ($bSuccess && !$this->getHouseCache()->isValid($sCacheFile)) {
            // cache found URLs in JSON
            $bSuccess = $this->getHouseCache()->save(json_encode($aUrls), $sCacheFile);
        }

        return $bSuccess;
    }

    /**
     * Process a house : parse from URL.
     *
     * @param string $sUrl house URL to parse.
     */
    private function processHouse($sUrl): void
    {
        $sHousePath = $this->getHousePath($sUrl);
        if (!$this->getHouseCache()->isValid($sHousePath)) {
            $this->getHouseCache()->save(file_get_contents($sUrl));
        }
        $oCity = $this->getCity();
        if ($this->isValidCity($oCity)) {
            $oHouse = $this->getHouse();
            $oHouse->setCity($oCity);
            $oHouse->setReferer($this->getName());
            $oHouse->setUrl($sUrl);

            $oHouse = $this->getHouseRepository()->insert($oHouse);
            if (null !== $oHouse) {
                $this->getHouseImages()->save($oHouse);
                // TODO send notification to user
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function parseHouses(User $oUser): bool
    {
        $bSuccess = false;
        $this->oUser = $oUser;
        if ($this->getHouseCache()->isValid($this->getCacheDirectory() . static::JSON_CACHE)) {
            foreach ($this->getHouseRepository()->getAllNonExistentByUrl(json_decode($this->getHouseCache()->getContent())) as $sUrl) {
                $this->processHouse($sUrl);
            }
            $bSuccess = true;
        }

        return $bSuccess;
    }
}
