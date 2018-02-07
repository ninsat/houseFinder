<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseFinder\SeLoger;

use Symfony\Component\DomCrawler\Crawler;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\House;
use YoannBlot\HouseFinder\Service\HouseFinder\AbstractHouseFinder;

/**
 * Class SeLogerService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder\SeLoger
 */
class SeLogerService extends AbstractHouseFinder
{
    /**
     * @inheritdoc
     */
    public function generateUrl(): string
    {
        $sUrl = '';
        $sUrl .= "http://www.seloger.com/list_responsive_ajax_main.htm?naturebien=1&idtt=1&div=2238&tri=d_dt_crea&";
        $aFields = [];
        if ($this->getUser()->getRent() > 0) {
            $aFields[] = "pxmax=" . $this->getUser()->getRent();
        }
        if ($this->getUser()->getSurface() > 0) {
            $aFields[] = "surfacemin=" . $this->getUser()->getSurface();
        }
        if ($this->getUser()->getBedrooms() > 0) {
            $aFields[] = "nb_chambresmin=" . $this->getUser()->getBedrooms();
        }
        if ($this->getUser()->getCities() > 0) {
            $aPostalCodes = [];
            foreach ($this->getUser()->getCities() as $oCity) {
                $aPostalCodes[] = $oCity->getPostalCode();
            }
            $aFields[] = "ci=" . implode(',', $aPostalCodes);
        }
        // TODO house type
        $aFields[] = 'idtypebien=2,13,14';

        $sUrl .= implode('&', $aFields);

        return $sUrl;
    }

    /**
     * @inheritdoc
     */
    protected function getUrls(): array
    {
        $sSelector = 'div.slideContent > a';
        $oCrawler = new Crawler('<html><body>' . $this->getHouseCache()->getContent() . '</body></html>');
        $aUrls = $oCrawler->filter($sSelector)->each(function (Crawler $oLinkElement) {
            return $oLinkElement->attr('href');
        });

        return $aUrls;
    }

    /**
     * @inheritdoc
     */
    protected function getUniqueId(string $sUrl): string
    {
        $sId = substr($sUrl, 0, strpos($sUrl, '.htm'));
        return substr($sId, strrpos($sId, '/') + 1);
    }

    /**
     * @inheritdoc
     */
    protected function parseCity(): City
    {
        $oCity = new City();
        $oCrawler = new Crawler($this->getHouseCache()->getContent());

        $oCity->setName($oCrawler->filter('input[name="ville"]')->attr('value'));
        $oCity->setPostalCode($oCrawler->filter('input[name="codepostal"]')->attr('value'));

        return $oCity;
    }

    /**
     * @inheritdoc
     */
    protected function getHouse(): House
    {
        $oHouse = new House();
        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        $oContentElement = $oCrawler->filter('div.resume > div.g-row')->first();

        $oHouse->setTitle(trim($oCrawler->filter('h1')->text()));
        $oHouse->setDescription($oCrawler->filter('input[name="description"]')->attr('value'));
        $oHouse->setPropertyType($oContentElement->filter('h2.c-h2')->text());

        foreach ($oContentElement->filter('ul.criterion > li') as $oDomElement) {
            if (false !== strpos($oDomElement->nodeValue, 'pièces')) {
                $oHouse->setPieces(intval($oDomElement->nodeValue));
            } elseif (false !== strpos($oDomElement->nodeValue, 'chambres')) {
                $oHouse->setBedrooms(intval($oDomElement->nodeValue));
            } elseif (false !== strpos($oDomElement->nodeValue, 'm²')) {
                $oHouse->setSurface(intval($oDomElement->nodeValue));
            }
        }

        $fRent = htmlentities($oContentElement->filter('a[href="#about_price_anchor"]')->text());
        $fRent = str_replace('&nbsp;', '', $fRent);
        $fRent = html_entity_decode($fRent);
        $fRent = trim(substr($fRent, 0, strpos($fRent, '€')));
        $oHouse->setRent(floatval($fRent));

        // retrieve images
        foreach ($oCrawler->filter('.carrousel__photos img.carrousel_image_visu') as $oImgElement) {
            $oHouse->addImage($oImgElement->getAttribute('src'));
        }

        return $oHouse;
    }

}