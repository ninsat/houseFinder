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

/**
 * Class AbstractHouseFinder.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder
 */
abstract class AbstractHouseFinder implements HouseCrawlerInterface
{

    const HTML_CACHE = 'links.html';
    const JSON_CACHE = 'links.json';

    use HouseCacheTrait, HouseTrait, CityTrait;

    /**
     * @var User user.
     */
    private $oUser;

    /**
     * SeLogerService constructor.
     *
     * @param HouseCacheService $oCacheService cache service.
     * @param HouseRepository $oHouseRepository house repository.
     * @param CityRepository $oCityRepository city repository.
     */
    public function __construct(
        HouseCacheService $oCacheService,
        HouseRepository $oHouseRepository,
        CityRepository $oCityRepository
    ) {
        $this->oHouseCacheService = $oCacheService;
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
     * Get a house from URL.
     *
     * @param string $sUrl url to parse.
     *
     * @return House|null matched house.
     */
    protected function parseHouse(string $sUrl): ?House
    {
        // create cache if necessary
        $sHousePath = $this->getHousePath($sUrl);
        if (!$this->getHouseCache()->isValid($sHousePath)) {
            $this->getHouseCache()->save(file_get_contents($sUrl));
        }

        // parse content
        $oHouse = $this->getHouse();
        $oHouse->setCity($this->getCity());

        return $oHouse;
    }

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
        $oFoundCity = $this->getCityRepository()->getOneByPostalCode($oCity->getPostalCode());
        if (null === $oFoundCity) {
            $oFoundCity = $this->getCityRepository()->insert($oCity);
        }

        return $oFoundCity;
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
        if (!$this->getHouseCache()->isValid($this->getCacheDirectory() . static::HTML_CACHE)) {
            $bSuccess = $this->getHouseCache()->save(file_get_contents($this->generateUrl()));
        }

        $aUrls = $this->getUrls();
        if ($bSuccess && !$this->getHouseCache()->isValid($this->getCacheDirectory() . static::JSON_CACHE)) {
            // cache found URLs in JSON
            $bSuccess = $this->getHouseCache()->save(json_encode($aUrls));
        }

        return $bSuccess;
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
                $oHouse = $this->parseHouse($sUrl);
                if (null !== $oHouse) {
                    $oHouse->setUrl($sUrl);
                    $this->getHouseRepository()->insert($oHouse);
                    // TODO send notification to user
                }
            }
            $bSuccess = true;
        }

        return $bSuccess;
    }

    /**
     * Get the current city object.
     *
     * @return City city.
     */
    protected abstract function parseCity(): City;
}
