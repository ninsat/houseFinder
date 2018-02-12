<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseFinder\AVendreALouer;

use YoannBlot\Framework\Utils\Text\Normalize;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Service\HouseCache\HouseCacheService;
use YoannBlot\HouseFinder\Service\HouseCache\HouseCacheTrait;

/**
 * Class LocalityFinderService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder\AVendreALouer
 */
class LocalityFinderService
{

    use HouseCacheTrait;

    /**
     * LocalityFinderService constructor.
     *
     * @param HouseCacheService $oCache cache.
     */
    public function __construct(HouseCacheService $oCache)
    {
        $this->oHouseCacheService = $oCache;
    }

    /**
     * Get locality id from city.
     *
     * @param City $oCity city.
     *
     * @return string locality id.
     */
    public function getLocalityId(City $oCity): string
    {
        $sLocalityId = '';
        $sCachePath = $this->getCachePath($oCity);
        if (!$this->getHouseCache()->isValid($sCachePath)) {
            $iTimestamp = strval(time());
            $iTimestamp .= substr($iTimestamp, -3);
            $sCitySearch = urlencode(strtolower(Normalize::removeAccents($oCity->getName()) . ' (' . $oCity->getPostalCode() . ')'));
            $sLocalityUrl = "https://www.avendrealouer.fr/common/api/localities?term=$sCitySearch&typeId.lte=&typeId.gte=&_=$iTimestamp";

            $aLocalities = json_decode(file_get_contents($sLocalityUrl), true);
            if (is_array($aLocalities) && count($aLocalities) > 0) {
                $sLocalityId = $aLocalities[0]['Value'];
                $this->getHouseCache()->save($sLocalityId);
            }
        } else {
            $sLocalityId = $this->getHouseCache()->getContent();
        }

        return $sLocalityId;
    }

    /**
     * Get the cache path.
     *
     * @param City $oCity city.
     *
     * @return string cache path.
     */
    private function getCachePath(City $oCity): string
    {
        return 'AVendreALouer' . DIRECTORY_SEPARATOR . 'Locality' . DIRECTORY_SEPARATOR . $oCity->getId();
    }

}