<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseFinder\Explorimmo;

use Symfony\Component\DomCrawler\Crawler;
use YoannBlot\Framework\Utils\Text\Normalize;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\House;
use YoannBlot\HouseFinder\Service\HouseFinder\AbstractHouseFinder;

/**
 * Class ExplorimmoService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder\Explorimmo
 */
class ExplorimmoService extends AbstractHouseFinder
{

    /**
     * @inheritdoc
     */
    public function generateUrl(): string
    {
        $sUrl = '';
        $sUrl .= 'http://www.explorimmo.com/resultat/annonces.html?transaction=';
        $sUrl .= $this->getUser()->isRental() ? 'location' : 'vente';
        $sUrl .= '&';

        $aFields = [];

        // TODO house type ?
        $aFields[] = "type=maison,appartement";

        if ($this->getUser()->isRental()) {
            if ($this->getUser()->getRent() > 0) {
                $aFields[] = "priceMax=" . $this->getUser()->getRent();
            }
        } else {
            $aFields[] = "priceMax=" . $this->getUser()->getMaxPrice();
        }
        if ($this->getUser()->getSurface() > 0) {
            $aFields[] = "areaMin=" . $this->getUser()->getSurface();
        }
        if ($this->getUser()->getBedrooms() > 0) {
            $aFields[] = "bedroomMin=" . $this->getUser()->getBedrooms();
        }
        if ($this->getUser()->getPieces() > 0) {
            $aFields[] = "roomMin=" . $this->getUser()->getPieces();
        }
        if ($this->getUser()->getCities() > 0) {
            $aLocations = [];
            foreach ($this->getUser()->getCities() as $oCity) {
                $aLocations[] = Normalize::removeAccents($oCity->getName()) . ' (' . substr($oCity->getPostalCode(), 0,
                        2) . ')';
            }
            $aFields[] = "location=" . implode(',', $aLocations);
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
            $aUrls = $oCrawler->filter('#vue h2 > a')
                ->each(function (Crawler $node) {
                    return 'http://www.explorimmo.com' . $node->attr('href');
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
        $sId = substr($sUrl, strrpos($sUrl, '-') + 1);
        return substr($sId, 0, strrpos($sId, '.'));
    }

    /**
     * @inheritdoc
     */
    protected function parseCity(): City
    {
        $oCity = new City();
        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        $sName = $oCrawler->filter('h1 > span.informations-localisation')->text();
        $sName = trim(str_replace('à', '', $sName));

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

        $sTitle = $oCrawler->filter('h1')->text();
        $sTitle = trim(substr($sTitle, 0, strpos($sTitle, 'à')));

        $oHouse->setTitle($sTitle);
        $sDescription = $oCrawler->filter('p#js-clicphone-description')->text();
        $sDescription = trim($sDescription);
        $oHouse->setDescription($sDescription);

        $fRent = Normalize::removeSpaces($oCrawler->filter('.container-price .price')->text());
        $fRent = str_replace(['€', 'CC'], '', $fRent);

        if ($this->getUser()->isRental()) {
            $oHouse->setRent(floatval($fRent));
        } else {
            $oHouse->setMaxPrice(intval($fRent));
        }

        foreach ($oCrawler->filter('.container-features ul.list-features > li') as $oLiElement) {
            /** @var \DOMElement $oLiElement */
            $sContent = trim($oLiElement->textContent);
            if (false !== strpos($sContent, 'pièces')) {
                $oHouse->setPieces(intval($sContent));
            } elseif (false !== strpos($sContent, 'm²')) {
                $oHouse->setSurface(intval($sContent));
            } elseif (false !== strpos($sContent, 'chambres')) {
                $oHouse->setBedrooms(intval($sContent));
            } elseif (false !== strpos($sContent, 'salle de bain')) {
                $iBathRooms = intval($sContent);
                $oHouse->setBath($iBathRooms > 0);
            }
        }

        // TODO
        // $oHouse->setTel($oCrawler->filter('.agency-contact > .agency-contact-left > .contact .info > span.label > a')->text());

        // get pictures
        $oCrawler->filter('.container-player img')->each(
            function (Crawler $oImage) use ($oHouse) {
                $oHouse->addImage($oImage->attr('src'));
            }
        );

        return $oHouse;
    }

}