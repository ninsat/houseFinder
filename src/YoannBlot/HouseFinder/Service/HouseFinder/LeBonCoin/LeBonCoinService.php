<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseFinder\LeBonCoin;

use Symfony\Component\DomCrawler\Crawler;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\House;
use YoannBlot\HouseFinder\Service\HouseFinder\AbstractHouseFinder;

/**
 * Class LeBonCoinService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder\LeBonCoin
 */
class LeBonCoinService extends AbstractHouseFinder
{

    /**
     * @inheritdoc
     */
    public function generateUrl(): string
    {
        $sUrl = '';
        $sUrl .= "https://www.leboncoin.fr/locations/offres/ile_de_france/?th=1&ros=3&ret=1&";
        $aFields = [];
        if ($this->getUser()->getRent() > 0) {
            $aFields[] = "mre=" . $this->getUser()->getRent();
        }
        if ($this->getUser()->getCities() > 0) {
            $aPostalCodes = [];
            foreach ($this->getUser()->getCities() as $oCity) {
                $aPostalCodes[] = urlencode($oCity->getName() . ' ' . $oCity->getPostalCode());
            }
            $aFields[] = "location=" . implode('%2', $aPostalCodes);
        }
        if ($this->getUser()->getSurface() > 0) {
            $aMatchSurfaces = [0, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 110, 120, 150, 300];
            foreach ($aMatchSurfaces as $iSurfaceKey => $iSurfaceValue) {
                if ($iSurfaceValue > $this->getUser()->getSurface()) {
                    $aFields[] = "sqs=" . ($iSurfaceKey - 1);
                    break;
                }
            }
        }
        $sUrl .= implode('&', $aFields);

        return $sUrl;
    }

    /**
     * @inheritdoc
     */
    protected function getUrls(): array
    {
        $sSelector = 'section.mainList ul > li > a.list_item';
        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        try {
            $aUrls = $oCrawler->filter($sSelector)->each(function (Crawler $oLinkElement) {
                $sUrl = str_replace('//', 'https://', $oLinkElement->attr('href'));
                return substr($sUrl, 0, strpos($sUrl, '.htm') + 4);
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
        $sUniqueId = substr($sUrl, strrpos($sUrl, '/') + 1);
        return substr($sUniqueId, 0, strpos($sUniqueId, '.'));
    }

    /**
     * @inheritdoc
     */
    protected function parseCity(): City
    {
        $oCity = new City();

        $oCrawler = new Crawler(utf8_encode($this->getHouseCache()->getContent()));
        $sName = $oCrawler->filter('div.line_city > h2 > .value')->text();
        $sName = trim($sName);
        $sPostalCode = substr($sName, strrpos($sName, ' ') + 1);
        $sName = str_replace($sPostalCode, '', $sName);

        $oCity->setName($sName);
        $oCity->setPostalCode($sPostalCode);

        return $oCity;
    }

    /**
     * @inheritdoc
     */
    protected function getHouse(): House
    {
        $oCrawler = new Crawler(utf8_encode($this->getHouseCache()->getContent()));
        $oHouse = new House();

        $sTitle = str_replace('\n', '', trim($oCrawler->filter('h1')->text()));
        $oHouse->setTitle($sTitle);
        $sDescription = $oCrawler->filter('div.properties_description > p.value')->text();
        $oHouse->setDescription($sDescription);

        foreach ($oCrawler->filter('section.properties.lineNegative > div.line > h2 > span.property') as $oDomElement) {
            /** @var \DOMElement $oDomElement */
            $sKey = trim($oDomElement->nodeValue);

            switch ($sKey) {
                case'Loyer mensuel':
                    $oHouse->setRent(floatval($oDomElement->parentNode->getAttribute('content')));
                    break;
                case'Pièces':
                    $oHouse->setPieces(intval($oDomElement->nextSibling->nextSibling->nodeValue));
                    break;
                case 'Surface':
                    $fSurface = $oDomElement->nextSibling->nextSibling->nodeValue;
                    $fSurface = str_replace('m2', '', $fSurface);
                    $oHouse->setSurface(intval($fSurface));
                    break;
                case 'Type de bien':
                    $sPropertyType = str_replace('\n', '', $oDomElement->nextSibling->nextSibling->nodeValue);
                    $oHouse->setPropertyType($sPropertyType);
                    break;
                default :
                    break;
            }
        }

        // TODO get telephone from api.leboncoin.fr
        // list_id:962096630
        // app_id:leboncoin_web_utils
        //key:54bb0281238b45a03f0ee695f73e704f
        //text:1

        // search inside description
        if (0 === $oHouse->getBedrooms()) {
            $iBedroomsPosition = strpos($sDescription, 'chambre');
            if (false !== $iBedroomsPosition) {
                $iBedrooms = substr($sDescription, 0, $iBedroomsPosition);
                $iBedrooms = trim($iBedrooms);
                $iBedrooms = substr($iBedrooms, strrpos($iBedrooms, ' '));
                $iBedrooms = trim($iBedrooms);
                $oHouse->setBedrooms(intval($iBedrooms));
            }
        }

        // get pictures
        $sJsImages = $oCrawler->filter('section.adview_main > script')->last()->text();
        preg_match_all("/images\[\d\] = \"(.*)\"/", $sJsImages, $aTmpImage);

        foreach ($aTmpImage[1] as $sImage) {
            $oHouse->addImage($sImage);
        }

        return $oHouse;
    }
}