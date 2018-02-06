<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Repository;

use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\House;

/**
 * Class HouseRepository.
 *
 * @package YoannBlot\HouseFinder\Model\Repository
 * @author  Yoann Blot
 *
 * @table   houses_house
 */
class HouseRepository extends AbstractRepository
{

    /**
     * @inheritDoc
     *
     * @return House[] all houses
     */
    public function getAll(string $sWhere = 'WHERE enabled = 1', string $sOrderBy = 'date desc', int $iLimit = 0): array
    {
        return parent::getAll($sWhere, $sOrderBy, $iLimit);
    }

    /**
     * Get last houses of a city.
     *
     * @param City $oCity city to retrieve houses.
     * @param int $iLimit limit of houses to get.
     *
     * @return House[] last houses of city.
     */
    public function getLast(City $oCity, int $iLimit = 10): array
    {
        $sWhere = 'WHERE enabled = 1 AND city_id = ' . $oCity->getId();
        $sOrderBy = 'date desc';

        return $this->getAll($sWhere, $sOrderBy, $iLimit);
    }

    /**
     * Get all non existent houses from given URL array, not already in database.
     *
     * @param string[] $aUrls urls.
     *
     * @return string[] non existent houses.
     */
    public function getAllNonExistentByUrl(array $aUrls): array
    {
        $sUrls = "'" . implode("','", $aUrls) . "'";
        $sWhere = "WHERE url in ($sUrls)";

        foreach ($this->getAll($sWhere) as $oHouse) {
            $sKey = array_search($oHouse->getUrl(), $aUrls);
            if (false !== $sKey) {
                unset($aUrls[$sKey]);
            }
        }

        return $aUrls;
    }
}