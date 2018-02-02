<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Repository;

use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class CityRepository
 *
 * @package YoannBlot\HouseFinder\Model\Repository
 * @author  Yoann Blot
 *
 * @table   houses_city
 */
class CityRepository extends AbstractRepository
{

    /**
     * @inheritDoc
     *
     * @return City[] matched cities.
     */
    public function getAll(string $sWhere = 'WHERE enabled = 1', string $sOrderBy = 'name asc', int $iLimit = 0): array
    {
        return parent::getAll($sWhere, $sOrderBy, $iLimit);
    }

}