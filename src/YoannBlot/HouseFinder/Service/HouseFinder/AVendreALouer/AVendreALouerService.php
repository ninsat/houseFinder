<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseFinder\AVendreALouer;

use Symfony\Component\DomCrawler\Crawler;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\House;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;
use YoannBlot\HouseFinder\Service\HouseCache\HouseCacheService;
use YoannBlot\HouseFinder\Service\HouseFinder\AbstractHouseFinder;
use YoannBlot\HouseFinder\Service\HouseImages\HouseImagesService;

/**
 * Class AVendreALouerService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder\AVendreALouer
 */
class AVendreALouerService extends AbstractHouseFinder
{
    /**
     * @var LocalityFinderService locality service.
     */
    private $oLocalityService;

    /**
     * AVendreALouerService constructor.
     *
     * @param HouseCacheService $oCacheService cache service.
     * @param HouseImagesService $oHouseImagesService house images service.
     * @param HouseRepository $oHouseRepository house repository.
     * @param CityRepository $oCityRepository city repository.
     * @param LocalityFinderService $oLocalityService locality service.
     */
    public function __construct(
        HouseCacheService $oCacheService,
        HouseImagesService $oHouseImagesService,
        HouseRepository $oHouseRepository,
        CityRepository $oCityRepository,
        LocalityFinderService $oLocalityService
    ) {
        parent::__construct($oCacheService, $oHouseImagesService, $oHouseRepository, $oCityRepository);
        $this->oLocalityService = $oLocalityService;
    }

    /**
     * @inheritdoc
     */
    public function generateUrl(): string
    {
        $sUrl = '';
        $sUrl .= "https://www.avendrealouer.fr/recherche.html?pageIndex=1&pageSize=100&sortPropertyName=ReleaseDate&sortDirection=Descending&searchTypeID=2&typeGroupCategoryID=6&transactionId=2";

        // TODO is house ?
        $iHouseGroupId = 48;

        $aFields = [];
        $aFields[] = "typeGroupIds=" . $iHouseGroupId;
        if ($this->getUser()->getRent() > 0) {
            $aFields[] = "maximumPrice=" . $this->getUser()->getRent();
        }
        if ($this->getUser()->getSurface() > 0) {
            $aFields[] = "minimumSurface=" . $this->getUser()->getSurface();
        }
        if ($this->getUser()->getBedrooms() > 0) {
            $aFields[] = "bedroomComfortIds=" . $this->getUser()->getBedrooms();
        }
        if ($this->getUser()->getPieces() > 0) {
            $aFields[] = "roomComfortIds=" . $this->getUser()->getPieces();
        }
        if ($this->getUser()->getCities() > 0) {
            $aLocalityIds = [];
            foreach ($this->getUser()->getCities() as $oCity) {
                $sLocalityId = $this->oLocalityService->getLocalityId($oCity);
                if ('' !== $sLocalityId) {
                    $aLocalityIds[] = $sLocalityId;
                }
            }
            $aFields[] = "localityIds=" . implode(',', $aLocalityIds);
        }
        $sUrl .= implode('&', $aFields);

        return $sUrl;
    }

    /**
     * @inheritdoc
     */
    protected function getUrls(): array
    {
        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        try {
            $aUrls = $oCrawler->filter('ul#result-list > li > a')
                ->each(function (Crawler $node) {
                    return 'http://www.avendrealouer.fr' . $node->attr('href');
                });
        } catch (\Exception $e) {
            $aUrls = [];
        }

        return $aUrls;
    }

    /**
     * @inheritdoc
     */
    protected function getUniqueId(string $sUrl): string
    {
        $sId = substr($sUrl, strrpos($sUrl, '/') + 1);

        return substr($sId, 0, strrpos($sId, '.'));
    }

    /**
     * @inheritdoc
     */
    protected function parseCity(): City
    {
        $oCity = new City();

        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        $sName = $oCrawler->filter('.fd-title > h1 > span.mainh1')->text();
        $sName = substr($sName, strrpos($sName, ',') + 1);

        $sPostalCode = substr($sName, strpos($sName, '(') + 1);
        $sPostalCode = substr($sPostalCode, 0, strpos($sPostalCode, ')'));

        $sName = trim(substr($sName, 0, strpos($sName, '(')));

        $oCity->setName($sName);
        $oCity->setPostalCode($sPostalCode);

        return $oCity;
    }

    /**
     * @inheritdoc
     */
    protected function getHouse(): House
    {
        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        $oHouse = new House();

        $oHouse->setTitle($oCrawler->filter('.fd-title > h1 > span.mainh1')->text());
        $oHouse->setHouse(false !== strpos(strtolower($oHouse->getTitle()), 'maison'));
        $oHouse->setDescription(trim($oCrawler->filter('#propertyDesc')->text()));

        $fRent = $oCrawler->filter('.fd-price > .price > .display-price > span')->text();
        $fRent = htmlentities($fRent);
        $fRent = str_replace('&nbsp;', '', $fRent);
        $fRent = html_entity_decode($fRent);
        $fRent = trim(substr($fRent, 0, strpos($fRent, '€')));
        $oHouse->setRent(floatval($fRent));

        foreach ($oCrawler->filter('#table td') as $oDomElement) {
            /** @var \DOMElement $oDomElement */
            $aChildNodes = $oDomElement->childNodes;
            $sKey = $aChildNodes->item(1)->textContent;
            $sValue = $aChildNodes->item(3)->textContent;

            if (0 === strpos($sKey, 'Surface:')) {
                $iSurface = substr($sValue, 0, strpos($sValue, ' '));
                $oHouse->setSurface(intval($iSurface));
            } elseif (0 === strpos($sKey, 'Pièce(s):')) {
                $oHouse->setPieces(intval($sValue));
            } elseif (0 === strpos($sKey, 'Chambre(s):')) {
                $oHouse->setBedrooms(intval($sValue));
            }
        }

        // TODO telephone
        // $oHouse->setTel($oCrawler->filter('#show-tel-fd-button')->attr('data-phone-number'));

        // get pictures
        $oCrawler->filter('#bxSliderContainer > ul > li > img[alt=""]')->each(function (Crawler $oImage) use ($oHouse) {
            $oHouse->addImage($oImage->attr('src'));
        });

        return $oHouse;
    }
}