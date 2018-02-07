<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseFinder\Orpi;

use Symfony\Component\DomCrawler\Crawler;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\House;
use YoannBlot\HouseFinder\Service\HouseFinder\AbstractHouseFinder;

/**
 * Class OrpiService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder\Orpi
 */
class OrpiService extends AbstractHouseFinder
{

    const MAX_ROOMS = 6;

    /**
     * @inheritdoc
     */
    public function generateUrl(): string
    {
        $sUrl = '';
        $sUrl .= 'https://www.orpi.com/recherche/ajax/rent?sort=date-up';

        $aFields = [];

        // TODO house type ?
        $aFields[] = "realEstateTypes[]=maison";

        if ($this->getUser()->getRent() > 0) {
            $aFields[] = "maxPrice=" . $this->getUser()->getRent();
        }
        if ($this->getUser()->getSurface() > 0) {
            $aFields[] = "minSurface=" . $this->getUser()->getSurface();
        }
        if ($this->getUser()->getBedrooms() > 0) {
            $aFields[] = "nbBedrooms=" . $this->getUser()->getBedrooms();
        }
        if ($this->getUser()->getPieces() > 0) {
            for ($iRooms = $this->getUser()->getPieces(); $iRooms <= static::MAX_ROOMS; $iRooms++) {
                $aFields[] = "nbRooms[]=$iRooms";
            }
        }
        if ($this->getUser()->getCities() > 0) {
            foreach ($this->getUser()->getCities() as $oCity) {
                $sCityName = str_replace(' ', '-', strtolower($oCity->getName()));
                // TODO remove accents
                $aFields[] = "locations[]=" . $sCityName;
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
        $oJson = json_decode($this->getHouseCache()->getContent());
        $aUrls = [];
        foreach ($oJson->items as $oHouse) {
            $aUrls[] = 'https://www.orpi.com/annonce-location-' . $oHouse->slug . '/';
        }

        return $aUrls;
    }

    /**
     * @inheritdoc
     */
    protected function getUniqueId(string $sUrl): string
    {
        $sId = substr($sUrl, strrpos($sUrl, '-') + 1);
        return substr($sId, 0, strrpos($sId, '/'));
    }

    /**
     * @inheritdoc
     */
    protected function parseCity(): City
    {
        $oCity = new City();
        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        $sName = $oCrawler->filter('.estateOffer address.address')->text();

        $iSeparatorPosition = strpos($sName, ' - ');
        if (false !== $iSeparatorPosition) {
            $sName = substr($sName, 0, $iSeparatorPosition);
        }
        $sName = trim($sName);

        $oCity->setName($sName);

        return $oCity;
    }

    /**
     * @inheritdoc
     */
    protected function getHouse(): House
    {
        $oHouse = new House();
        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        $oHouse->setTitle($oCrawler->filter('h1.h1.cap .text')->text());
        $sDescription = $oCrawler->filter('.paragraphs-textcell')->text();
        $sDescription = trim($sDescription);
        $oHouse->setDescription($sDescription);

        $oOfferCrawler = $oCrawler->filter('.estateOffer');

        $fRent = $oOfferCrawler->filter('.estateOffer-price .current-price > .price')->text();
        $fRent = str_replace(['€', ' '], '', $fRent);
        $fRent = trim($fRent);
        $oHouse->setRent(floatval($fRent));

        foreach ($oOfferCrawler->filter('.estate-characteristic-right ul > li') as $oLiElement) {
            /** @var \DOMElement $oLiElement */
            $sContent = trim($oLiElement->textContent);
            if (0 === strpos($sContent, 'Dépôt')) {
                $fGuarantee = substr($sContent, strrpos($sContent, ':') + 1);
                $fGuarantee = str_replace(['€', ' '], '', $fGuarantee);
                $oHouse->setGuarantee(floatval($fGuarantee));
            } elseif (false !== strpos($sContent, 'Honoraires')) {
                $fFees = substr($sContent, strrpos($sContent, ':') + 1);
                $fFees = str_replace(['€', ' '], '', $fFees);
                $oHouse->setFees(floatval($fFees));
            }
        }

        foreach ($oOfferCrawler->filter('.estateOffer-location > .surface > span') as $oSpanElement) {
            /** @var \DOMElement $oSpanElement */
            $sContent = trim($oSpanElement->textContent);
            if (false !== strpos($sContent, 'pièces')) {
                $oHouse->setPieces(intval($sContent));
            } elseif (false !== strpos($sContent, 'chambres')) {
                $oHouse->setBedrooms(intval($sContent));
            } elseif (false !== strpos($sContent, 'm2')) {
                $oHouse->setSurface(intval($sContent));
            }
        }

        // TODO
        // $oHouse->setTel($oCrawler->filter('.agency-contact > .agency-contact-left > .contact .info > span.label > a')->text());

        // get pictures
        $oCrawler->filter('.estate-carousel-nav-dots img')->each(
            function (Crawler $oImage) use ($oHouse) {
                $oHouse->addImage($oImage->attr('src'));
            }
        );

        return $oHouse;
    }

}