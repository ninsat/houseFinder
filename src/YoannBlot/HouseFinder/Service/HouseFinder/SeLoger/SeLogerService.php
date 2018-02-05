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
        $sUrl .= "http://www.seloger.com/list.htm?idtt=1&tri=d_dt_crea&";
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
        $oCrawler = new Crawler($this->getHouseCache()->getContent());
        $aUrls = $oCrawler->filter('body div.content section.liste_resultat article div.listing_infos > h2 > a')
            ->each(function (Crawler $oLinkElement) {
                return $oLinkElement->attr('href');
            });

        return $aUrls;
    }

    /**
     * @inheritdoc
     */
    protected function getUniqueId(string $sUrl): string
    {
        // TODO
        return 'test';
    }

    /**
     * @inheritdoc
     */
    protected function parseHouse(string $sUrl): ?House
    {
        // TODO
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function getHouse(): House
    {
        // TODO: Implement getHouse() method.
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function parseCity(): City
    {
        // TODO: Implement parseCity() method.
        return null;
    }

}